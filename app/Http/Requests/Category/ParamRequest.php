<?php

namespace App\Http\Requests\Category;

use Illuminate\Validation\Rule;
use App\Http\Requests\FormRequest;

class ParamRequest extends FormRequest
{
    public function rules()
    {
        return [
            'category_id' => [

            ],
            'name' => [
                'required',
                'min:2',
                'max:6',
                Rule::unique('product_brands')->where(function ($query) {
                    if ($this->route('brand')) {
                        $query = $query->where('id', '<>', $this->route('brand')->id);
                    }
                    return $query->where('shop_id', $this->route('shop')->id)->whereNull('deleted_at');
                }),
            ],
            'values' => [
                'required'
            ],
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => '所属分类',
            'name' => '参数名',
            'values' => '参数值',
        ];
    }
}
