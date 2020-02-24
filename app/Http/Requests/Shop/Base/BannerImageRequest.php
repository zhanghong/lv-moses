<?php

namespace App\Http\Requests\Shop\Base;

use App\Http\Requests\Shop\FormRequest;

class BannerImageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'image' => [
                'required',
                'dimensions:min_width=100,max_width=1024,min_height=30,max_height=1000',
                'min:20', // 最小10KB
                'max:5120', // 最大5M
            ],
        ];
    }
}
