<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\Shop\FormRequest;

class ImageRequest extends FormRequest
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
