<?php

namespace App\Http\Controllers\Shop\Base;

use App\Models\Shop\Shop;
use App\Models\Shop\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Shop\CategoryRequest;
use App\Http\Resources\Shop\CategoryResource;
use App\Http\Controllers\Shop\Controller;

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
        $params = $request->only($allow_names);

        $skip_names = ['parent_id', 'is_enabled', 'order'];
        foreach ($skip_names as $key => $name) {
            if (isset($params[$name]) && $params[$name] === '') {
                // 使用数据库默认值或记录当前值
                unset($params[$name]);
            }
        }
        return $params;
    }
}