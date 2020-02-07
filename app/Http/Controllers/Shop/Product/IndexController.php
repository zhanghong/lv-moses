<?php

namespace App\Http\Controllers\Shop\Product;

use App\Models\Shop\Shop;
use Illuminate\Http\Request;
use App\Http\Requests\Base\FieldUniqueRequest;
use App\Http\Controllers\Shop\Controller;
use App\Exceptions\LogicException;

class IndexController extends Controller
{
    public function index(Shop $shop)
    {
        $user = Auth::user();
        return ['user' => $user];
    }

    public function store(ProductRequest $request, Shop $shop)
    {

    }

    public function show(Shop $shop, Product $product)
    {

    }

    public function update(ProductRequest $request, Shop $shop, Product $product)
    {

    }

    public function destroy(Shop $shop, Product $product)
    {

    }

    // 验证店铺名称等字段值是否唯一
    public function unique(FieldUniqueRequest $request, Shop $shop)
    {
        $id = $request->input('id');
        $name = $request->input('name', '');
        $value = $request->input('value', '');

        $wheres = [
            ['shop_id', '=', $shop->id]
        ];
        if ($id > 0) {
            $wheres[] = ['id', '<>', $id];
        }
        try {
            $flag = Product::checkAttrUnique($name, $value, $wheres);
        } catch (LogicException $e) {
            return $e;
        }

        return ['code' => 200, 'message' => '字段值唯一'];
    }
}
