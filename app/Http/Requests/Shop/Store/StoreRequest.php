<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\Shop\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [

        ];
    }

    public function attributes()
    {
        return [
            'name' => '名称',
            'contact_name' => '联系人',
            'contact_phone' => '联系方式',
            'contact_address' => '联系地址',
            'order' => '排序编号',
            'is_enabled' => '是否启用',
        ];
    }
}
