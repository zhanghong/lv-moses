<?php

namespace App\Http\Requests\Shop;

use Illuminate\Validation\Rule;
use App\Http\Requests\FormRequest;

class BrandRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'min:2',
                'max:20',
                Rule::unique('shop_brands')->where(function ($query) {
                    if ($this->route('brand')) {
                        $query = $query->where('id', '<>', $this->route('brand')->id);
                    }
                    return $query->where('shop_id', $this->route('shop')->id)->whereNull('deleted_at');
                }),
            ],
            'description' => 'max:200',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '名称',
            'logo_url' => 'Logo图片',
            'description' => '品牌介绍',
        ];
    }
}
