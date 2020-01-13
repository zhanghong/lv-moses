<?php

namespace App\Http\Requests\shop;

use App\Http\Requests\FormRequest;

class MainImageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'file' => [
                'required',
                'dimensions:min_width=200,min_height=200',
                'min:20', // 最小20KB
                'max:5120', // 最大5M
            ],
        ];
    }
}
