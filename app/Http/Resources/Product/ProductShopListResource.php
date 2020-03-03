<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductShopListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'title' => $this->title,
            'long_title' => $this->long_title,
            'main_image_url' => $this->main_image_url,
            'fiction_count' => $this->fiction_count,
            'sold_count' => $this->sold_count,
            'market_price' => $this->market_price,
            'sell_price' => $this->sell_price,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,
            'group' => $this->group,
            'brand' => $this->brand,
            'order' => $this->order,
            'can_update' => $this->can_update,
            'can_delete' => $this->can_delete,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
