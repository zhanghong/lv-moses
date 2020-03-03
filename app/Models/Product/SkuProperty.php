<?php

namespace App\Models\Product;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category\Property;
use App\Models\Category\Selector;

class SkuProperty extends Model
{
    protected $table = 'product_sku_properties';

    public function sku()
    {
        return $this->belongsTo(Sku::class);
    }

    public function Property()
    {
        return $this->belongsTo(Property::class);
    }

    public function selector()
    {
        return $this->belongsTo(Selector::class);
    }

    /**
     * 查询出商品已关联的所有属性信息
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-02-28
     * @param    Product            $product   商品实例
     * @return   array
     */
    public static function productChoicedProperties($product)
    {
        $list = [
            'choicedStardards' => [],
            'choicedParams' => [],
        ];

        if (!$product || !$product->has_skus) {
            return $list;
        }

        $property_ids = static::select('property_id')
                            ->where('product_id', $product->id)
                            ->groupBy('property_id')
                            ->get()
                            ->map(function($item){
                                return $item->property_id;
                            });
        if ($property_ids->isEmpty()) {
            return $list;
        }

        $selects = [
            'category_properties.id AS property_id',
            'category_properties.name AS property_name',
            'category_properties.type AS property_type',
            'category_selectors.id AS selector_id',
            'category_selectors.name AS selector_name',
            DB::raw('COUNT(product_sku_properties.id) AS checked_count'), // 是否选中
        ];
        $group_list = Property::select($selects)
                        ->join('category_selectors', 'category_properties.id', '=', 'category_selectors.property_id')
                        ->leftJoin('product_sku_properties', function($join) use ($product_id) {
                            $join->on('category_selectors.id', '=', 'product_sku_properties.selector_id')
                                ->where('product_sku_properties.product_id', '=', $product_id);
                        })
                        ->whereIn('category_properties.id', $property_ids->toArray())
                        ->whereNull('category_selectors.deleted_at')
                        ->groupBy('category_properties.id', 'category_selectors.id')
                        ->get()
                        ->groupBy('property_type');


        foreach ($group_list as $type => $selectors) {
            $group_items = [];
            foreach ($selectors as $idx => $item) {
                $key = 'prop_' . $item->property_id;
                $s_item = [
                    'id' => $item->selector_id,
                    'name' => $item->selector_name,
                ];

                if ($item->checked_count) {
                    $s_item['checked'] = true;
                    $s_item['locked'] = true;
                } else {
                    $s_item['checked'] = false;
                    $s_item['locked'] = false;
                }

                if (!isset($group_items[$key])) {
                    $group_items[$key] = [
                        'id' => $item->property_id,
                        'name' => $item->property_name,
                        'selectors' => [],
                    ];
                }
                $group_items[$key]['selectors'][] = $s_item;
            }
            switch ($type) {
                case Property::TYPE_STANDARDS:
                    $list['choicedStardards'] = array_values($group_items);
                    break;
                case Property::TYPE_PARAMS:
                    $list['choicedParams'] = array_values($group_items);
                    break;
            }
        }
        return $list;
    }

    /**
     * 通过所属商品Sku创建或更新记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-02-11
     * @param    array              $params 表单数据
     * @param    Sku                $sku    商品实例
     * @return   SkuProperty
     */
    public static function syncBySku($params, $sku)
    {
        $ids = [];
        foreach ($params as $pid => $sid) {
            if (empty($pid) || empty($sid)) {
                continue;
            }

            $item = static::where('sku_id', $sku->id)->where('property_id', $pid)->first();
            if (empty($item)) {
                $item = new static;
                $item->shop_id = $sku->shop_id;
                $item->product_id = $sku->product_id;
                $item->sku_id = $sku->id;
                $item->property_id = $pid;
            }
            $item->selector_id = $sid;
            $item->save();

            $ids[] = $item->id;
        }

        $query = static::where('sku_id', $sku->id);
        if ($ids) {
            $query = $query->whereNotIn('id', $ids);
        }
        $query->delete();

        return true;
    }
}
