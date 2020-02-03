<?php

namespace App\Models;

use App\Exceptions\LogicException;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    public const OUTER_FROM_WECHAT = 'wechat';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $fields = static::fillableFieldNames();
        $this->fillable($fields);
    }

    public function editor()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 排序作用域
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-18
     * @param    Query              $query Query实例
     * @param    string             $order 排序方式
     * @return   Query
     */
    public function scopeWithOrder($query, $order = '')
    {
        if ($order) {
            $order = strtoupper($order);
        }
        switch ($order) {
            case 'ASC':
                $query->orderBy('order', 'ASC')->orderBY('id', 'ASC');
                break;
            default:
                $query->orderBy('order', 'DESC')->orderBY('id', 'DESC');
                break;
        }
        return $query;
    }

    /**
     * 实例是否允许被更新（业务逻辑）
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-09
     * @return   bool
     */
    public function getIsAllowUpdateAttribute()
    {
        return true;
    }

    /**
     * 实例是否允许用户更新（业务逻辑+权限验证）
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-09
     * @return   bool
     */
    public function getCanUpdateAttribute()
    {
        if (!$this->is_allow_update) {
            return false;
        }
        return true;
    }

    /**
     * 实例是否已删除
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-09
     * @return   bool
     */
    public function getIsDeletedAttribute()
    {
        // 取出软删除字段值
        $time = $this->getAttribute('deleted_at');
        if (is_null($time)) {
            // 删除时间不为空表示已删除
            return false;
        }
        return true;
    }

    /**
     * 实例是否允许被删除（业务逻辑）
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-09
     * @return   bool
     */
    public function getIsAllowDeleteAttribute()
    {
        return true;
    }

    /**
     * 实例是否允许用户删除（业务逻辑+权限验证）
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-09
     * @return   bool
     */
    public function getCanDeleteAttribute()
    {
        if (!$this->is_allow_delete) {
            return false;
        }
        return true;
    }

    /**
     * 允许检测值唯一是否唯一的字段列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-14
     * @return   Collection
     */
    protected static function allowUniqueAttrs()
    {
        return [];
    }

    /**
     * 检测字段值是否唯一
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-15
     * @param    string             $name    字段名
     * @param    string             $value   字段值
     * @param    array              $conditons 附加条件
     * @return   array
     */
    protected static function checkAttrUnique(string $name, string $value, array $conditons = []): array
    {
        if (!$name) {
            throw new LogicException(LogicException::UNIQUE_FIELD_NAME_EMPTY);
        }

        $allow_attrs = static::allowUniqueAttrs();
        if (!in_array($name, $allow_attrs)) {
            throw new LogicException(LogicException::UNIQUE_FIELD_NAME_DISALLOW);
        }

        if (!$value) {
            throw new LogicException(LogicException::UNIQUE_FIELD_VALUE_EMPTY);
        }

        $query = static::where($name, $value);

        if ($conditons) {
            $query = $query->where($conditons);
        }

        if (method_exists(static::class, 'initializeSoftDeletes')) {
            // 当前类应用了软删除，需要过滤掉软删除记录
            $column = defined('static::DELETED_AT') ? static::DELETED_AT : 'deleted_at';
            $query = $query->whereNull($column);
        }

        if ($query->count()) {
            throw new LogicException(LogicException::UNIQUE_FIELD_VALUE_PRESENT);
        }

        return [
            'code' => LogicException::STATUS_OK,
            'message' => '',
        ];
    }

    /**
     * 允许表单更新字段列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-17
     * @return   array
     */
    public static function parseFields() {
        return collect([]);
    }

    /**
     * 模型允许填充字段列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-17
     * @return   array
     */
    public static function fillableFieldNames() {
        return static::parseFields()->map(function ($field, $key) {
            return $field['name'];
        })->all();
    }

    /**
     * 过滤并填充属性
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-17
     * @param    array              $data   属性值列表
     * @param    Collection/array   $fields 允许填充属性列表
     * @return   Model
     */
    public function parseFill($data, $fields = null) {
        if (!$fields) {
            $fields = static::parseFields();
        } else if (is_array($fields)) {
            $fields = collect($fields);
        }

        if (!$fields->isEmpty()) {
            $data = $this->filterFieldParams($fields, $data);
        }

        $this->fill($data);
        return $this;
    }

    /**
     * 过滤模型属性值
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-07
     * @param    Collection         $fields 字段列表
     * @param    array              $params 提交数据
     * @return   array
     */
    protected function filterFieldParams(Collection $fields, array $params):array
    {
        $data = [];

        if($fields->isEmpty() || empty($params)){
            return $data;
        }

        foreach ($fields as $key => $f_item) {
            if(!isset($f_item['name'])) {
                continue;
            }
            $name = $f_item['name'];

            if (isset($f_item['alias'])) {
                $p_name = $f_item['alias'];
            } else {
                $p_name = $name;
            }
            if(isset($params[$p_name])){
               $value = $params[$p_name];
            }else if(isset($f_item['default'])){
                $value = $f_item['default'];
            }else{
                continue;
            }

            $fieldType = '';
            if(isset($f_item['type'])){
               $fieldType = $f_item['type'];
            }

            switch ($fieldType) {
                case 'array':
                    if (!is_array($value)) {
                        $value = [];
                    }
                    break;
                case 'int':
                case 'integer':
                    $value = intval($value);
                    break;
                case 'float':
                    $value = floatval($value);
                    break;
                case 'bool':
                case 'boolean':
                    $value = boolval($value);
                    break;
                default:
                    try {
                        $value = trim($value);
                    } catch (\Exception $e) {
                        $value = '';
                    }
                    break;
            }

            $data[$name] = $value;
        }

        return $data;
    }

    /**
     * 把列表转化成树状结构
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2018-11-16
     * @param    array              $list 列表
     * @return   array                   [description]
     */
    protected static function hierarchical($list){
        $result = [];
        foreach ($list as $key => $node) {
            $node->setRelation('children', new Collection);
            $result[$node->id] = $node;
        }

        $nestedKeys = [];

        foreach($result as $key => $node) {
            $parentKey = intval($node->parent_id);
            $result[$parentKey]->children[] = $node;
            $nestedKeys[] = $node->id;
        }

        foreach($nestedKeys as $key){
          unset($result[$key]);
        }

        if(empty($result)){
            return [];
        }

        $root_node = NULL;
        foreach ($result as $key => $value) {
            $root_node = $value;
            break;
        }
        return $root_node->children;
    }

    /**
     * 把数据列表转化成树状结构
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-01-16
     * @param    array              $field_nodes 结点列表
     * @return   array                           [description]
     */
    public static function convertListToTree($field_nodes){
        $nodes = [
            [
                'children' => [],
            ]
        ];

        foreach ($field_nodes as $index => $node) {
            $node['children'] = [];
            $nodes[$node['id']] = $node;
        }

        $nested_ids = [];
        foreach ($nodes as $id => $node) {
            if(!isset($node['id'])){
                continue;
            }
            $nodes[$node['parent_id']]['children'][] = &$nodes[$id];
            $nested_ids[] = $id;
        }

        foreach ($nested_ids as $key => $id) {
            unset($nodes[$id]);
        }

        if(isset($nodes[0])){
            $root_node = $nodes[0];
            return $root_node['children'];
        }
        return $nodes;
    }
}
