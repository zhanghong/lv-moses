<?php

namespace App\Http\Controllers\Shop\Product;

use Illuminate\Http\Request;
use App\Models\Product\StandardProperty;
use App\Http\Requests\Shop\Product\StandardRequest;
use App\Http\Requests\Base\FieldUniqueRequest;
use App\Http\Resources\Category\ShopPropertyResource;
use App\Http\Controllers\Shop\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StandardController extends Controller
{
    public function index()
    {
        $paginate = StandardProperty::withOrder('ASC')
                        ->shoped($this->shop->id)
                        ->with('selectors')
                        ->paginate();
        return ShopPropertyResource::collection($paginate);
    }

    public function store(StandardRequest $request)
    {
        $params = $request->all();
        $property = new StandardProperty;
        $property->type = StandardProperty::TYPE_STANDARDS;
        $property->choice = StandardProperty::CHOICE_CHECKBOX;
        $property->shop()->associate($this->shop);
        $property = $property->updateInfo($params);
        return new ShopPropertyResource($property);
    }

    public function show($id)
    {
        $property = $this->findById($id, true);
        return new ShopPropertyResource($property);
    }

    public function update(StandardRequest $request, $id)
    {
        $property = $this->findById($request->id, true);
        $params = $request->all();
        $property->updateInfo($params);
        return new ShopPropertyResource($property);
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
            $flag = StandardProperty::checkAttrUnique($name, $value, $wheres);
        } catch (LogicException $e) {
            return $e;
        }

        return ['code' => 200, 'message' => '字段值唯一'];
    }

    public function destroy($id)
    {
        $property = $this->findById($id);
        $property->delete();
        return $this->responseData([]);
    }

    public function findById($id, $with_relation = false)
    {
        $property = null;
        $query = StandardProperty::shoped($this->shop->id);
        if ($with_relation) {
            $query = $query->with('selectors');
        }
        if ($id) {
            $property = $query->where('id', $id)->first();
        }

        if (!$property) {
            throw (new ModelNotFoundException)->setModel(StandardProperty::class, $id);
        }
        return $property;
    }
}
