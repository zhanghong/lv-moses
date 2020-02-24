<?php

namespace App\Http\Controllers\Shop\Product;

use Illuminate\Http\Request;

use App\Models\Shop\Shop;
use App\Models\Product\Product;
use App\Models\Category\Category;
use App\Http\Controllers\Shop\Controller;
use App\Http\Requests\Shop\Product\ProductRequest;
use App\Http\Resources\Product\ProductDetailResource as DetailResource;
use App\Http\Resources\Category\CategoryShopDetailResource;

class IndexController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return ['user' => $user];
    }

    public function category(Request $request)
    {
        $category_id = $request->category_id;
        $category = Category::find($category_id);
        return new CategoryShopDetailResource($category);
    }

    public function store(ProductRequest $request)
    {
        $params = $request->all();
        $product = new Product;
        $product->shop()->associate($this->shop);
        $product->updateInfo($params);
        return new DetailResource($product);
    }

    public function show(Product $product)
    {
        return new DetailResource($product);
    }

    public function update(ProductRequest $request, Product $product)
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

    public function destroy(Product $product)
    {
        $store->delete();
        return $this->responseData([], 204);
    }
}
