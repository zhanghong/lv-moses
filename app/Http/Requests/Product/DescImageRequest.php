<?php

namespace App\Http\Requests\Product;

use App\Models\Product\Product;
use App\Http\Requests\FormRequest;

class DescImageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'image' => [
                'required',
                'mimes:jpeg,png,jpg',
                'dimensions:min_width=' . Product::IMAGE_DESC_MIN_WIDTH . ',min_height=' . Product::IMAGE_DESC_MAX_HEIGHT,
                'max:' . Product::IMAGE_DESC_MAX_SIZE,
            ],
        ];
    }
}
