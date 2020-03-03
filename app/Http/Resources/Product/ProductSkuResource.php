<?php

namespace App\Http\Resources\Product;

use App\Models\Category\Selector;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSkuResource extends JsonResource
{
    public function toArray($request)
    {
        $properties = $this->properties()->orderBy('selector_id', 'ASC')->get();

        $selector_names = [];
        if ($properties->isEmpty()) {
            $selector_ids = [];
            $selector_names = '';
        } else {
            $selector_ids = $properties->map(function($prop) {
                                                return strval($prop->selector_id);
                                            })->toArray();
            $selector_names = Selector::whereIn('id', $selector_ids)
                            ->orderBy('id', 'ASC')
                            ->get()
                            ->map(function($item) {
                                return $item->name;
                            })->join('|');
        }

        return [
            'id' => $this->id,
            // 'product_id' => $this->product_id,
            // 'main_image_id' => $this->main_image_id,
            // 'main_image_url' => $this->main_image_url,
            'selector_ids' => $selector_ids,
            'selector_ids_str' => implode('|', $selector_ids),
            'selector_names' => $selector_names,
            'market_price' => $this->market_price,
            'sell_price' => $this->sell_price,
            'cost_price' => $this->cost_price,
            'integral' => $this->integral,
            'stock' => $this->stock,
            'is_active' => true,
            'can_change' => false,
        ];
    }
}
