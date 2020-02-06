<?php

namespace App\Http\Controllers\Shop\Product;

use Illuminate\Http\Request;
use App\Models\Category\Category;
use App\Http\Controllers\Shop\Controller;
use App\Http\Resources\Product\CategoryDetailResource;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $list = [];
        $pid = $request->get('pid');
        $pid = intval($pid);
        if ($pid > 0) {
            $list = Category::selectByParentId($pid)
                            ->mapWithKeys(function ($cat) {
                                return [$cat->id => $cat->name];
                            });
        }

        return ['data' => $list, 'code' => 200];
    }

    public function show(Category $category, Request $request)
    {
        return new CategoryDetailResource($category);
    }
}
