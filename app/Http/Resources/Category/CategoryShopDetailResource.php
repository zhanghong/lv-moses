<?php

namespace App\Http\Resources\Category;

use App\Models\Category\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryShopDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $standards = [];
        $params = [];

        $query = Property::with('selectors');
        $shop = $request->route('shop');
        if ($shop) {
            $query = $query->where(function($query) use ($shop) {
                $query->where('shop_id', 0)
                    ->orWhere('shop_id', $shop->id);
            });
        } else {
            $query = $query->where('shop_id', 0);
        }
        $properties = $query->whereHas('cat_mids', function (Builder $query) {
            return $query->where('category_id', $this->id);
        })->get();

        foreach ($properties as $key => $prop) {
            $resource = new CategoryPropertyResource($prop);
            switch ($prop->type) {
                case Property::TYPE_STANDARDS:
                    $standards[] = $resource;
                    break;
                case Property::TYPE_PARAMS:
                    $params[] = $resource;
                    break;

            }
        }

        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'standards' => $standards,
            'params' => $params,
        ];
    }
}
