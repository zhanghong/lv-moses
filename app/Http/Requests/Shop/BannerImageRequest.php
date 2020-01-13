<?php

namespace App\Http\Requests\shop;

use App\Http\Requests\FormRequest;

class BannerImageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'file' => [
                'required',
                'dimensions:min_width=100,max_width=1024,min_height=30,max_height=1000',
                'min:10', // 最小10KB
                'max:200', // 最大200KB
            ],
        ];
    }
}
