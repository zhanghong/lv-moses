<?php

namespace App\Models\Product;

use App\Models\Category\Selector;
use App\Models\Category\StandardProperty as Base;

class StandardProperty extends Base
{
    public function scopeShoped($query, $shop_id)
    {
        return $query->where('shop_id', $shop_id);
    }

    /**
     * 允许检测值唯一是否唯一的字段列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-14
     * @return   array
     */
    protected static function allowUniqueAttrs()
    {
        return ['name'];
    }

    /**
     * 允许表单更新字段列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-17
     * @return   array
     */
    public static function parseFields() {
        return collect([
            ['name' => 'name', 'type' => 'string', 'default' => ''],
            ['name' => 'choice', 'type' => 'int'],
            ['name' => 'is_enabled', 'type' => 'boolean'],
            ['name' => 'order', 'type' => 'integer', 'default' => 0],
        ]);
    }

    public function updateInfo($params)
    {
        $this->parseFill($params);
        if ($this->save()) {
            $selectors = $params['selectors'] ?? [];
            Selector::attachPropertyValues($this, $params['selectors']);
        }

        return $this;
    }
}
