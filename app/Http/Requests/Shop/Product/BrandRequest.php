<?php

namespace App\Http\Requests\Shop\Product;

use Illuminate\Validation\Rule;
use App\Http\Requests\Shop\FormRequest;

class BrandRequest extends FormRequest
{
    public function rules()
    {
        $shop_id = $this->currentShopId();
        return [
            'name' => [
                'required',
                'min:2',
                'max:20',
                Rule::unique('product_brands')->where(function ($query) use ($shop_id) {
                    if ($this->route('brand')) {
                        $query = $query->where('id', '<>', $this->route('brand')->id);
                    }
                    return $query->where('shop_id',$shop_id)->whereNull('deleted_at');
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
