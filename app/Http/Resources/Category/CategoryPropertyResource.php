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
        $query = $this->selectors()->where('shop_id', 0);
        if ($request->route('shop')) {
            $query = $query->whereOr('shop_id', $request->route('shop')->id);
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
