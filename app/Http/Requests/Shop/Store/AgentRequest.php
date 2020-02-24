<?php

namespace App\Http\Requests\Store;

use App\Models\Store\Agent;
use App\Http\Requests\Shop\FormRequest;

class AgentRequest extends FormRequest
{
    public function rules()
    {
        $shop_id = $this->currentShopId();
        return [
            'name' => [
                'required',
                'min:2',
                'max:20',
                function ($attribute, $value, $fail) use ($shop_id) {
                    $wheres = [['shop_id', '=', $shop_id]];

                    if ($this->route('agent')) {
                        $wheres[] = ['id', '<>', $this->route('agent')->id];
                    }
                    try {
                        Agent::checkAttrUnique('name', $value, $wheres);
                    } catch (LogicException $e) {
                        $fail('经销商名称 已经存在。');
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
            'name' => '经销商名称',
            'contact_name' => '联系人',
            'contact_phone' => '联系方式',
            'contact_address' => '联系地址',
            'order' => '排序编号',
            'is_enabled' => '是否启用',
        ];
    }
}
