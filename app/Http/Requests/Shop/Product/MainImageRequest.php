<?php

namespace App\Http\Requests\Product;

use App\Models\Product\Product;
use App\Http\Requests\Shop\FormRequest;

class MainImageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'image' => [
                'required',
                'mimes:jpeg,png,jpg',
                'dimensions:min_width=' . Product::IMAGE_MAIN_MIN_WIDTH . ',min_height=' . Product::IMAGE_MAIN_MIN_HEIGHT,
                'max:' . Product::IMAGE_MAIN_MAX_SIZE,
            ],
        ];
    }
}
