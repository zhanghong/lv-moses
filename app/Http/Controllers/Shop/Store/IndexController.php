<?php

namespace App\Http\Controllers\Shop\Store;

use App\Models\Shop\Shop;
use App\Models\Store\Store;
use App\Http\Requests\Store\StoreRequest;
use App\Http\Requests\Base\FieldUniqueRequest;
use App\Http\Resources\Store\StoreListResource as ListResource;
use App\Http\Resources\Store\StoreDetailResource as DetailResource;
use App\Http\Controllers\Shop\Controller;
use App\Exceptions\LogicException;

class IndexController extends Controller
{
    public function index(Shop $shop)
    {
        $paginate = Store::where('shop_id', $shop->id)->paginate();
        return ListResource::collection($paginate);
    }

    public function store(StoreRequest $request, Shop $shop)
    {
        $params = $request->all();
        $store = new Store;
        $store->shop()->associate($shop);
        $store->updateInfo($params);
        return new DetailResource($store);
    }

    public function show(Shop $shop, Store $store)
    {
        return new DetailResource($store);
    }

    public function update(StoreRequest $request, Shop $shop, Store $store)
    {
        try {
            $store->updateInfo($request->all());
            return new DetailResource($store);
        } catch (\ErrorException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Shop $shop, Store $store)
    {
        $store->delete();
        return $this->responseData([], 204);
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
            $flag = Store::checkAttrUnique($name, $value, $wheres);
        } catch (LogicException $e) {
            return $e;
        }

        return ['code' => 200, 'message' => '字段值唯一'];
    }
}
