<?php

namespace App\Http\Controllers\Shop\Product;

use Illuminate\Http\Request;

use App\Models\Shop\Shop;
use App\Models\Product\Brand;
use App\Http\Requests\Shop\Product\BrandRequest;
use App\Http\Resources\Product\BrandResource;
use App\Http\Controllers\Shop\Controller;

class BrandController extends Controller
{
    public function index()
    {
        $paginate = Brand::withOrder('ASC')
                        ->where('shop_id', $this->shop->id)
                        ->paginate();
        return BrandResource::collection($paginate);
    }

    public function store(BrandRequest $request)
    {
        $params = $request->all();
        $brand = new Brand;
        $brand->parseFill($params);
        $brand->shop()->associate($this->shop);
        $brand->save();
        return new BrandResource($brand);
    }

    public function show(Brand $brand)
    {
        return new BrandResource($brand);
    }

    public function update(BrandRequest $request, Brand $brand)
    {
        $params = $request->all();
        $brand->parseFill($params);
        $brand->save();
        return new BrandResource($brand);
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return $this->responseData([]);
    }
}
