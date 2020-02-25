<?php

namespace App\Http\Controllers\Shop\Product;

use App\Models\Product\Group;
use App\Exceptions\LogicException;
use App\Http\Requests\Base\FieldUniqueRequest;
use App\Http\Requests\Shop\Product\GroupRequest;
use App\Http\Resources\Product\GroupResource;
use App\Http\Controllers\Shop\Controller;

class GroupController extends Controller
{
    public function index()
    {
        $paginate = Group::withOrder('ASC')
                        ->where('shop_id', $this->shop->id)
                        ->paginate();
        return GroupResource::collection($paginate);
    }

    public function store(GroupRequest $request)
    {
        $params = $request->all();
        $group = new Group;
        $group->parseFill($params);
        $group->shop()->associate($this->shop);
        $group->save();
        return new GroupResource($group);
    }

    public function show(Group $group)
    {
        return new GroupResource($group);
    }

    public function update(GroupRequest $request, Group $group)
    {
        $params = $request->all();
        $group->parseFill($params);
        $group->save();
        return new GroupResource($group);
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
            $flag = Group::checkAttrUnique($name, $value, $wheres);
        } catch (LogicException $e) {
            return $e;
        }

        return ['code' => 200, 'message' => '字段值唯一'];
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return $this->responseData([]);
    }
}
