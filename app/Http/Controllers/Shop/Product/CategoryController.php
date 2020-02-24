<?php

namespace App\Http\Controllers\Shop\Product;

use Illuminate\Http\Request;
use App\Models\Shop\Shop;
use App\Models\Product\Category;
use App\Http\Requests\Shop\Product\CategoryRequest;
use App\Http\Resources\Product\CategoryResource;
use App\Http\Controllers\Shop\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $paginate = Category::withOrder('ASC')
                        ->where('shop_id', $this->shop->id)
                        ->paginate();
        return CategoryResource::collection($paginate);
    }

    public function store(CategoryRequest $request)
    {
        $params = $request->all();
        $category = new Category;
        $category->parseFill($params);
        $category->shop()->associate($this->shop);
        $category->save();
        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $params = $request->all();
        $category->parseFill($params);
        $category->save();
        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return $this->responseData([]);
    }
}
