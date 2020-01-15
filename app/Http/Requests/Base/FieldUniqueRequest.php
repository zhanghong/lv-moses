<?php

namespace App\Http\Requests\Base;

use App\Http\Requests\FormRequest;

class FieldUniqueRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'value' => [
                'required',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '字段名',
            'value' => '字段值',
        ];
    }
}
