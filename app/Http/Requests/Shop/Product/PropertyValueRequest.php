<?php

namespace App\Http\Requests\Shop\Product;

use App\Http\Requests\Shop\FormRequest;

class PropertyValueRequest extends FormRequest
{
    public function rules()
    {
        return [
            'values' => [
                'required',
                'min:1',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'values' => '规格值',
        ];
    }
}
