<?php

namespace App\Models\Product;

use App\Models\Category\Selector;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sku extends Model
{
    use SoftDeletes;

    protected $table = 'product_skus';

    protected static function boot()
    {
        parent::boot();

        self::saved(function (Sku $sku) {
            $sku->syncPropertieRecords();
        });
    }

    // 上传附件类型-SKU图片
    public const UPLOAD_TYPE_IMAGE_MAIN = 'sku_main';
    protected $casts = [
        'properties_content' => 'json',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function properties()
    {
        return $this->hasMany(SkuProperty::class);
    }

    /**
     * 允许表单更新字段列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-17
     * @return   Collection
     */
    public static function parseFields() {
        return collect([
            ['name' => 'market_price', 'type' => 'float'],
            ['name' => 'sell_price', 'type' => 'float', 'default' => 0],
            ['name' => 'cost_price', 'type' => 'float', 'default' => 0],
            ['name' => 'integral', 'type' => 'int', 'default' => 0.0],
            ['name' => 'stock', 'type' => 'string', 'default' => ''],
            ['name' => 'order', 'type' => 'string', 'default' => static::ORDER_DEFAULT],
        ]);
    }

    /**
     * 通过所属商品创建或更新记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-02-11
     * @param    array              $params 表单数据
     * @param    Product            $product 商品实例
     * @return   Sku
     */
    public static function syncByProduct($params, $product)
    {
        $skus = [];
        $sku_ids = [];

        foreach ($params as $key => $item) {
            if (empty($item['selector_ids'])) {
                $selector_ids = [];
            }else if (is_array($item['selector_ids'])) {
                $selector_ids = $item['selector_ids'];
            } else {
                $selector_ids = explode(',', $item['selector_ids']);
            }

            if (empty($selector_ids)) {
                $selectors = collect([]);
            } else {
                $selectors = Selector::whereIn('id', $selector_ids)
                        ->where(function($query) use ($product) {
                            $query->where('shop_id', 0)->orWhere('shop_id', $product->shop_id);
                        })
                        ->orderBY('id', 'ASC')
                        ->get();
            }

            $selector_ids = $selectors->map(function ($item) {
                                            return $item->id;
                                        })->join('|');

            // 可以使用已删除sku
            $query = static::withTrashed()->where('product_id', $product->id);
            $id = $item['id'] ?? null;
            if ($id) {
                $query = $query->where('id', $id);
            } else {
                $query = $query->where('selector_ids', $selector_ids);
            }

            if (!$sku = $query->first()) {
                $sku = new static;
                $sku->shop_id = $product->shop_id;
                $sku->product_id = $product->id;
            } else if ($sku->deleted_at) {
                $sku->restore();
            }

            $sku->selector_ids = $selector_ids;
            $sku->properties_content = $selectors->mapWithKeys(function ($item) {
                                                        return [$item->property_id => $item->id];
                                                    });

            $sku->parseFill($item);
            $sku->save();

            $first_main_image = null;
            $main_image_ids = $item['image_ids'] ?? [];
            if ($main_image_ids) {
                Upload::ownSet($sku->shop, Product::UPLOAD_TYPE_IMAGE_MAIN, $sku, $main_image_ids);
                $first_main_image = Upload::ownFirst($sku->shop, Product::UPLOAD_TYPE_IMAGE_MAIN, $sku, ['order' => 'ASC']);
            }
            // 商品主图片
            if ($first_main_image) {
                $sku->main_image_id = $first_main_image->id;
                $sku->main_image_url = $first_main_image->file_path;
            } else {
                $sku->main_image_id = 0;
                $sku->main_image_url = '';
            }
            $sku->save();

            array_push($skus, $sku);
            array_push($sku_ids, $sku->id);
        }

        $query = static::where('product_id', $product->id);
        if (!empty($sku_ids)) {
            $query = $query->whereNotIn('id', $sku_ids);
        }
        $query->delete();

        return $skus;
    }

    /**
     * 同步属性信息到SkuProperty
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-02-13
     * @return   boolean
     */
    private function syncPropertieRecords()
    {
        $ids = [];
        if (!empty($this->properties_content)) {
            foreach ($this->properties_content as $pid => $sid) {
                $item = SkuProperty::where('sku_id', $this->id)->where('property_id', $pid)->first();
                if (!$item) {
                    $item = new SkuProperty;
                    $item->shop_id = $this->shop_id;
                    $item->product_id = $this->product_id;
                    $item->sku_id = $this->id;
                    $item->property_id = $pid;
                }
                $item->selector_id = $sid;
                $item->save();
                $ids[] = $item->id;
            }
        }

        $query = SkuProperty::where('sku_id', $this->id);
        if ($ids) {
            $query = $query->whereNotIn('id', $ids);
        }
        $query->delete();

        return true;
    }
}
