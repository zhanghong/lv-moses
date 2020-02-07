<?php

namespace App\Http\Controllers\Shop\Category;

use Illuminate\Http\Request;
use App\Models\Category\Category;
use App\Models\Shop\Shop;
use App\Http\Controllers\Shop\Controller;
use App\Http\Resources\Category\CategoryShopDetailResource;

class IndexController extends Controller
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

    public function show(Request $request, Shop $shop, Category $category)
    {
        return new CategoryShopDetailResource($category);
    }
}
