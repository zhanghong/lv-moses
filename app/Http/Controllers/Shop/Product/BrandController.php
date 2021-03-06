<?php

namespace App\Http\Controllers\Shop\Product;

use App\Models\Product\Brand;
use App\Exceptions\LogicException;
use App\Http\Requests\Base\FieldUniqueRequest;
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

    // 验证店铺名称等字段值是否唯一
    public function unique(FieldUniqueRequest $request)
    {
        $id = $request->input('id');
        $name = $request->input('name', '');
        $value = $request->input('value', '');

        $wheres = [
            ['shop_id', '=', $this->shop->id]
        ];
        if ($id > 0) {
            $wheres[] = ['id', '<>', $id];
        }
        try {
            $flag = Brand::checkAttrUnique($name, $value, $wheres);
        } catch (LogicException $e) {
            return $e;
        }

        return ['code' => 200, 'message' => '字段值唯一'];
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return $this->responseData([]);
    }
}
