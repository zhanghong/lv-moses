<?php

namespace App\Http\Controllers\Shop\Product;

use Illuminate\Http\Request;
use App\Models\Shop\Shop;
use App\Models\Product\Category;
use App\Http\Requests\Product\CategoryRequest;
use App\Http\Resources\Product\CategoryResource;
use App\Http\Controllers\Shop\Controller;

class CategoryController extends Controller
{
    public function index(Shop $shop)
    {
        $paginate = Category::withOrder('ASC')
                        ->where('shop_id', $shop->id)
                        ->paginate();
        return CategoryResource::collection($paginate);
    }

    public function store(CategoryRequest $request, Shop $shop)
    {
        $params = $request->all();
        $category = new Category;
        $category->parseFill($params);
        $category->shop()->associate($shop);
        $category->save();
        return new CategoryResource($category);
    }

    public function show(Shop $shop, Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, Shop $shop, Category $category)
    {
        $params = $request->all();
        $category->parseFill($params);
        $category->save();
        return new CategoryResource($category);
    }

    public function destroy(Shop $shop, Category $category)
    {
        $category->delete();
        return $this->responseData([]);
    }
}
