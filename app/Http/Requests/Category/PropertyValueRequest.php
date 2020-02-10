<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

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
