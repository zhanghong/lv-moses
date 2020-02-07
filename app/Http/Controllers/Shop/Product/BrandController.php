<?php

namespace App\Http\Controllers\Shop\Product;

use Illuminate\Http\Request;

use App\Models\Shop\Shop;
use App\Models\Product\Brand;
use App\Http\Requests\Product\BrandRequest;
use App\Http\Resources\Product\BrandResource;
use App\Http\Controllers\Shop\Controller;

class BrandController extends Controller
{
    public function index(Shop $shop)
    {
        $paginate = Brand::withOrder('ASC')
                        ->where('shop_id', $shop->id)
                        ->paginate();
        return BrandResource::collection($paginate);
    }

    public function store(BrandRequest $request, Shop $shop)
    {
        $params = $request->all();
        $brand = new Brand;
        $brand->parseFill($params);
        $brand->shop()->associate($shop);
        $brand->save();
        return new BrandResource($brand);
    }

    public function show(Shop $shop, Brand $brand)
    {
        return new BrandResource($brand);
    }

    public function update(BrandRequest $request, Shop $shop, Brand $brand)
    {
        $params = $request->all();
        $brand->parseFill($params);
        $brand->save();
        return new BrandResource($brand);
    }

    public function destroy(Shop $shop, Brand $brand)
    {
        $brand->delete();
        return $this->responseData([]);
    }
}
