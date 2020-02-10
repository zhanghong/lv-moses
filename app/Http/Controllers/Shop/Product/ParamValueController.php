<?php

namespace App\Http\Controllers\Shop\Product;

use App\Models\Shop\Shop;
use App\Models\Category\Property;
use App\Models\Category\Selector;
use App\Http\Requests\Category\PropertyValueRequest;
use App\Http\Resources\Category\CategoryPropertyResource;
use App\Http\Controllers\Shop\Controller;

class ParamValueController extends Controller
{
    public function store(PropertyValueRequest $request, Shop $shop, Property $property)
    {
        Selector::attachPropertyValues($property, $request->values, $shop->id);
        return new CategoryPropertyResource($property);
    }
}
