<?php

namespace App\Models\Product;

class Detail extends Model
{
    protected $table = 'product_details';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * 允许表单更新字段列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-17
     * @return   Collection
     */
    public static function parseFields() {
        return collect([
            ['name' => 'main_image_id', 'type' => 'int', 'default' => 0],
            ['name' => 'integral', 'type' => 'int', 'default' => 0.0],
            ['name' => 'introduce', 'type' => 'string', 'default' => ''],
        ]);
    }

    /**
     * 通过所属商品创建或更新记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-02-11
     * @param    array              $params 表单数据
     * @param    Product            $product 商品实例
     * @return   Detail
     */
    public static function updateOrCreateByProduct($params, $product)
    {
        $detail = static::where('product_id', $product->id)->first();
        if (empty($detail)) {
            $detail = new static;
            $detail->product_id = $product->id;
        }
        $detail->parseFill($params);
        $detail->save();
        return $detail;
    }
}
