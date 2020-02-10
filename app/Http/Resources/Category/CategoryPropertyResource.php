<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryPropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $shop = $request->route('shop');
        $query = $this->selectors();
        if ($shop) {
            $query = $query->where(function($query) use ($shop) {
                $query->where('shop_id', 0)->orWhere('shop_id', $shop->id);
            });
        } else {
            $query = $query->where('shop_id', 0);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'selectors' => $query->withOrder('ASC')->get()->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            }),
        ];
    }
}
