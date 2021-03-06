<?php

namespace App\Http\Controllers\Shop\Store;

use App\Models\Shop\Shop;
use App\Models\Store\Agent;
use App\Http\Requests\Base\FieldUniqueRequest;
use App\Http\Requests\Shop\Store\AgentRequest;
use App\Http\Resources\Store\AgentResource;
use App\Http\Controllers\Shop\Controller;
use App\Exceptions\LogicException;

class AgentController extends Controller
{
    public function index()
    {
        $paginate = Agent::withOrder('DESC')->where('shop_id', $this->shop->id)->paginate();
        return AgentResource::collection($paginate);
    }

    public function list()
    {
        $options =  Agent::withOrder('DESC')
                    ->where('shop_id', $this->shop->id)
                    ->get()
                    ->map(function ($agent, $key) {
                        return [
                            'value' => $agent->id,
                            'label' => $agent->name,
                        ];
                    });
        return ['data' => $options];
    }

    public function store(AgentRequest $request)
    {
        $agent = new Agent;
        $agent->shop()->associate($this->shop);
        $agent->parseFill($request->all());
        $agent->save();
        return new AgentResource($agent);
    }

    public function show(Agent $agent)
    {
        return new AgentResource($agent);
    }

    public function update(AgentRequest $request, Agent $agent)
    {
        $params = $request->all();
        $agent->parseFill($params);
        $agent->save();
        return new AgentResource($agent);
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();
        return response()->json(null, 204);
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
            $flag = Agent::checkAttrUnique($name, $value, $wheres);
        } catch (LogicException $e) {
            return $e;
        }

        return ['code' => 200, 'message' => '字段值唯一'];
    }
}
