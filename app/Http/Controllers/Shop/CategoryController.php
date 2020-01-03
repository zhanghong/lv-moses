<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop\Shop;
use App\Models\Shop\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CategoryRequest;
use App\Http\Resources\Shop\CategoryResource;

class CategoryController extends Controller
{
    public function index(Shop $shop)
    {
        $paginate = $shop->categories()->paginate();
        return CategoryResource::collection($paginate);
    }

    public function store(CategoryRequest $request, Shop $shop)
    {
        $params = $this->filterRequestParams($request);
        $category = new Category($params);
        $shop->categories()->save($category);
        return new CategoryResource($category);
    }

    public function show(Shop $shop, Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, Shop $shop, Category $category)
    {
        $params = $this->filterRequestParams($request);
        return $params;
        $category->fill($params);
        $category->save();
        return new CategoryResource($category);
    }

    public function destroy(Shop $shop, Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }

    private function filterRequestParams($request)
    {
        $allow_names = [
            'name',
            'base_category_id',
            'parent_id',
            'icon_url',
            'is_enabled',
            'order'
        ];
        return $request->only($allow_names);
    }
}
