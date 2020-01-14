<?php

namespace App\Models;

use App\Exceptions\LogicException;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
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
     * @return   array
     */
    protected static function allowUniqueAttrs()
    {
        return [];
    }

    /**
     * 检测字段值是否唯一
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-14
     * @param    string             $name  字段名
     * @param    string             $value 字段值
     * @param    int                $id    主键ID
     * @return   bool
     */
    protected static function checkAttrUnique($name, $value, $id)
    {
        if (empty($name)) {
            throw new LogicException(LogicException::UNIQUE_FIELD_NAME_EMPTY);
        }

        $allow_attrs = static::allowUniqueAttrs();
        if (!in_array($name, $allow_attrs)) {
            throw new LogicException(LogicException::UNIQUE_FIELD_NAME_DISALLOW);
        }

        if (empty($value)) {
            throw new LogicException(LogicException::UNIQUE_FIELD_VALUE_EMPTY);
        }

        $query = static::where($name, $value);
        if ($id) {
            $query = $query->where('id', '<>', intval($id));
        }

        if (method_exists(static::class, 'initializeSoftDeletes')) {
            // 当前类应用了软删除，需要过滤掉软删除记录
            $column = defined('static::DELETED_AT') ? static::DELETED_AT : 'deleted_at';
            $query = $query->whereNull($column);
        }

        if ($query->count()) {
            throw new LogicException(LogicException::UNIQUE_FIELD_VALUE_PRESENT);
        }

        return true;
    }

    /**
     * 过滤模型属性值
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-07
     * @param    array              $fields 字段列表
     * @param    array              $params 提交数据
     * @return   array
     */
    protected static function filterFieldParams(array $fields, array $params):array
    {
        $data = [];
        if(empty($fields) || empty($params)){
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
                    $value = intval($value);
                    break;
                case 'bool':
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
}
