<?php

namespace App\Http\Requests\Shop\Base;

use App\Http\Requests\Shop\FormRequest;

class MainImageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'image' => [
                'required',
                'dimensions:min_width=200,min_height=200',
                'min:20', // 最小20KB
                'max:5120', // 最大5M
            ],
        ];
    }
}
