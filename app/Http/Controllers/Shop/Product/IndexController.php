<?php

namespace App\Http\Controllers\Shop\Product;

use Illuminate\Http\Request;

use App\Models\Shop\Shop;
use App\Models\Product\Product;
use App\Models\Category\Category;
use App\Http\Controllers\Shop\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Resources\Product\ProductDetailResource as DetailResource;
use App\Http\Resources\Category\CategoryShopDetailResource;

class IndexController extends Controller
{
    public function index(Shop $shop)
    {
        $user = Auth::user();
        return ['user' => $user];
    }

    public function category(Request $request, Shop $shop)
    {
        $category_id = $request->category_id;
        $category = Category::find($category_id);
        return new CategoryShopDetailResource($category);
    }

    public function store(ProductRequest $request, Shop $shop)
    {
        $params = $request->all();
        $product = new Product;
        $product->shop()->associate($shop);
        $product->updateInfo($params);
        return new DetailResource($product);
    }

    public function show(Shop $shop, Product $product)
    {
        return new DetailResource($product);
    }

    public function update(ProductRequest $request, Shop $shop, Product $product)
    {
        try {
            $product->updateInfo($request->all());
            return new DetailResource($product);
        } catch (\ErrorException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Shop $shop, Product $product)
    {
        $store->delete();
        return $this->responseData([], 204);
    }
}
