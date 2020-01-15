<?php

namespace App\Http\Controllers\Shop\Store;

use App\Models\Shop\Shop;
use App\Models\Store\Agent;
use App\Http\Requests\Store\AgentRequest;
use App\Http\Resources\Store\AgentResource;
use App\Http\Controllers\Shop\Controller;

class AgentController extends Controller
{
    public function index(Shop $shop)
    {
        $paginate = Agent::where('shop_id', $shop->id)->paginate();
        return AgentResource::collection($paginate);
    }

    public function store(AgentRequest $request, Shop $shop)
    {
        $params = $this->filterRequestParams($request);
        $agent = new Agent($params);
        $agent->shop()->associate($shop);
        $agent->save();
        return new AgentResource($agent);
    }

    public function show(Shop $shop, Agent $agent)
    {
        return new AgentResource($agent);
    }

    public function update(AgentRequest $request, Shop $shop, Agent $agent)
    {
        $params = $this->filterRequestParams($request);
        $agent->fill($params);
        $agent->save();
        return new AgentResource($agent);
    }

    public function destroy(Shop $shop, Agent $agent)
    {
        $agent->delete();
        return response()->json(null, 204);
    }

    private function filterRequestParams($request)
    {
        $allow_names = [
            'name',
            'contact_name',
            'contact_phone',
            'is_enabled',
            'order',
        ];
        $params = $request->only($allow_names);

        $skip_names = ['is_enabled', 'order'];
        foreach ($skip_names as $key => $name) {
            if (isset($params[$name]) && $params[$name] === '') {
                // 使用数据库默认值或记录当前值
                unset($params[$name]);
            }
        }
        return $params;
    }
}
