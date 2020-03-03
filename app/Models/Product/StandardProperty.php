<?php

namespace App\Models\Product;

use App\Models\Category\Selector;
use App\Models\Category\StandardProperty as Base;

class StandardProperty extends Base
{
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
        DB::transaction(function () use ($params) {
            $this->parseFill($params);
            if ($this->save()) {
                $selectors = $params['selectors'] ?? [];
                Selector::attachPropertyValues($this, $params['selectors']);
            }
        });

        return $this;
    }

    public function productChoided($product_id)
    {
        $list = DB::table('category_properties a')
                    ->join('category_selectors b', 'a.id', '=', 'b.property_id')
                    ->join('product_sku_properties c', 'b.id', '=', 'c.selector_id')
                    ->select('a.id AS property_id', 'a.name AS property_name', 'b.id AS selector_id', 'b.name AS selector_name')
                    ->where([
                        ['a.type', '=', static::TYPE_STANDARDS],
                        ['c.product_id', '=', $product_id],
                    ])
                    ->whereNull('a.deleted_at')
                    ->whereNull('b.deleted_at')
                    ->groupBy('a.id, b.id')
                    ->having('COUNT(c.id) > 0')
                    ->get();
        return $list;
    }
}
