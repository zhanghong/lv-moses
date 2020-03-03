<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Base\UploadResource;

class ProductDetailResource extends JsonResource
{
    public function toArray($request)
    {
        if ($this->has_skus) {
            $skus = $this->skus;
        } else {
            $skus = collect([]);
        }

        $product_json = [
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'type' => $this->type,
            'title' => $this->title,
            'long_title' => $this->long_title,
            'group_id' => $this->group_id,
            'brand_id' => $this->brand_id,
            'main_image_url' => $this->main_image_url,
            'description' => $this->description,
            'rating' => $this->rating,
            'fiction_count' => $this->fiction_count,
            'sold_count' => $this->sold_count,
            'market_price' => floatval($this->market_price),
            'sell_price' => floatval($this->min_sell_price),
            'stock' => $this->stock,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,
            'order' => $this->order,
            'can_update' => $this->can_update,
            'can_delete' => $this->can_delete,
            'images' => $this->main_images->map(function($image) {
                return new UploadResource($image);
            }),
            'desc_images' => $this->desc_images->map(function($image) {
                return new UploadResource($image);
            }),
            'skus' => $skus->map(function($sku) {
                return new ProductSkuResource($sku);
            }),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];

        $detail = $this->detail;
        if ($detail) {
            $detail_json = [
                'main_image_id' => $detail->main_image_id,
                'integral' => $detail->integral,
                'introduce' => $detail->introduce,
            ];
        } else {
            $detail_json = [
                'main_image_id' => null,
                'integral' => 0,
                'introduce' => '',
            ];
        }

        return array_merge($product_json, $detail_json);
    }
}
