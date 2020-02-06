<?php

namespace App\Http\Resources\Product;

use App\Models\Category\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryDetailResource extends JsonResource
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

        $properties = Property::with('selectors')->where('shop_id', 0)->whereHas('cat_mids', function (Builder $query) {
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
