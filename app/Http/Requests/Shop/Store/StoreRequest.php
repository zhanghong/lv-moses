<?php

namespace App\Http\Requests\Shop\Store;

use App\Models\Store\Agent;
use App\Models\Store\Store;
use App\Http\Requests\Shop\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        $shop_id = $this->currentShopId();
        return [
            'agent_id' => [
                'required',
                'min:1',
                function ($attribute, $value, $fail) use ($shop_id) {
                    if (!Agent::where('id', $value)->where('shop_id', $shop_id)->count()) {
                        $fail('所属经销商 错误。');
                    }
                },
            ],
            'name' => [
                'required',
                'min:2',
                'max:20',
                function ($attribute, $value, $fail) use ($shop_id) {
                    $wheres = [['shop_id', '=', $shop_id]];

                    if ($this->route('store')) {
                        $wheres[] = ['id', '<>', $this->route('store')->id];
                    }
                    try {
                        Store::checkAttrUnique('name', $value, $wheres);
                    } catch (LogicException $e) {
                        $fail('门店名称 已经存在。');
                    }
                },
            ],
            'contact_name' => [
                'required',
                'min:2',
                'max:20',
            ],
            'contact_phone' => [
                'required',
                'max:30',
            ],
            'contact_address' => [
                'max: 100',
            ],
            'order' => [
                'integer',
                'min:0'
            ],
        ];
    }

    public function attributes()
    {
        return [
            '所属经销商' => '名称',
            'name' => '门店名称',
            'area_id' => '所在区域',
            'address' => '地址',
            'contact_name' => '联系人',
            'contact_phone' => '联系方式',
            'contact_address' => '联系地址',
            'order' => '排序编号',
            'is_enabled' => '是否启用',
        ];
    }
}
