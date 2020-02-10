<?php

namespace App\Http\Controllers\Shop\Product;

use App\Models\Shop\Shop;
use App\Models\Category\Category;
use App\Models\Category\Property;
use App\Http\Requests\Category\StandardRequest;
use App\Http\Resources\Category\CategoryShopDetailResource;
use App\Http\Controllers\Shop\Controller;

class StandardController extends Controller
{
    public function store(StandardRequest $request, Shop $shop)
    {
        $category_id = $request->category_id;
        $name = $request->name;
        $values = $request->values;
        Property::findOrCreateTypeItem(Property::TYPE_STANDARDS, $category_id, $name, $values, $shop->id);
        $category = Category::find($category_id);
        return new CategoryShopDetailResource($category);
    }
}
