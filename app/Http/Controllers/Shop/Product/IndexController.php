<?php

namespace App\Http\Controllers\Shop\Product;

use Illuminate\Http\Request;

use App\Models\Product\Brand;
use App\Models\Product\Group;
use App\Models\Product\Product;
use App\Models\Product\SkuProperty;
use App\Models\Category\StandardProperty;
use App\Models\Category\Selector;
use App\Http\Controllers\Shop\Controller;
use App\Http\Requests\Shop\Product\ProductRequest;
use App\Http\Resources\Product\ProductDetailResource as DetailResource;
use App\Http\Resources\Product\ProductShopListResource as ListResource;
use App\Http\Resources\Category\CategoryShopDetailResource;

class IndexController extends Controller
{
    public function index()
    {
        $paginate = Product::withOrder('ASC')
                        ->shoped($this->shop->id)
                        ->with(['group', 'brand'])
                        ->paginate();
        return ListResource::collection($paginate);
    }

    public function form(Request $request)
    {
        $shop_id = $this->shop->id;

        $data = [];
        $data['brands'] = Brand::withOrder('ASC')
                    ->shoped($shop_id)
                    ->get()->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                        ];
                    });

        $data['groups'] = Group::withOrder('ASC')
                    ->shoped($shop_id)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                        ];
                    });

        try {
            $product = $this->findById($request->id);
            $product_id = $product->id;
        } catch (\Exception $e) {
            $product = null;
        }
        $properties = SkuProperty::productChoicedProperties($product);
        $data = array_merge($data, $properties);

        return response()->json(['data' => $data]);
    }

    public function store(ProductRequest $request)
    {
        $params = $request->all();
        $product = new Product;
        $product->type = Product::TYPE_NORMAL;
        $product->shop()->associate($this->shop);
        $product->updateInfo($params);
        return new DetailResource($product);
    }

    public function show(Request $request)
    {
        $product = $this->findById($request->id, true);
        return new DetailResource($product);
    }

    public function update(ProductRequest $request)
    {
        try {
            $product = $this->findById($request->id);
            $product->updateInfo($request->all());
            return new DetailResource($product);
        } catch (\ErrorException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        $product = $this->findById($request->id);
        $product->delete();
        return $this->responseData([], 204);
    }

    public function findById($id, $with_relation = false)
    {
        $query = Product::shoped($this->shop->id);
        if ($with_relation) {
            $query = $query->with(['detail', 'skus.properties']);
        }
        return $query->findOrFail($id);
    }
}
