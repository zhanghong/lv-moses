<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopPropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $query = $this->selectors()->whereIn('shop_id', [0, $this->shop_id]);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'order' => $this->order,
            'selectors' => $query->withOrder('ASC')->get()->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            }),
        ];
    }
}
