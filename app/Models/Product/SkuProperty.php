<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class SkuProperty extends Model
{
    protected $table = 'product_sku_properties';

    public function sku()
    {
        return $this->belongsTo(Sku::class);
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
