<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Shop;
use App\Models\Shop\Brand;
use Illuminate\Http\Request;
use App\Http\Requests\Shop\BrandRequest;

class BrandController extends Controller
{
    public function index(Shop $shop)
    {
        return $shop->brands()->paginate();
    }

    public function store(BrandRequest $request, Shop $shop)
    {
        $brand = new Brand($request->all());
        $shop->brands()->save($brand);
        return $brand;
    }

    public function show(Shop $shop, Brand $brand)
    {
        if ($shop->id != $brand->shop_id) {
            return [];
        }
        return $brand;
    }

    public function update(BrandRequest $request, Shop $shop, Brand $brand)
    {
        if ($shop->id != $brand->shop_id) {
            return [];
        }

        $brand->fill($request->all());
        $brand->save();
        return $brand;
    }

    public function destroy(Shop $shop, Brand $brand)
    {
        $brand->delete();
        return [];
    }
}
