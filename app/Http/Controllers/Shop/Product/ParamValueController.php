<?php

namespace App\Http\Controllers\Shop\Product;

use App\Models\Shop\Shop;
use App\Models\Category\Property;
use App\Models\Category\Selector;
use App\Http\Requests\Shop\Product\PropertyValueRequest;
use App\Http\Resources\Category\CategoryPropertyResource;
use App\Http\Controllers\Shop\Controller;

class ParamValueController extends Controller
{
    public function store(PropertyValueRequest $request, Property $property)
    {
        Selector::attachPropertyValues($property, $request->values, $this->shop->id);
        return new CategoryPropertyResource($property);
    }
}
