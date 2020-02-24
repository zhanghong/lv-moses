<?php

namespace App\Http\Requests\Category;

use Illuminate\Validation\Rule;
use App\Http\Requests\Shop\FormRequest;

class ParamRequest extends FormRequest
{
    public function rules()
    {
        $shop_id = $this->currentShopId();
        return [
            'category_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $query = Category::where('id', $value)->where('shop_id', $shop_id);
                    if (!$query->count()){
                        $fail('所属店铺分类 不存在。');
                    }
                },
            ],
            'name' => [
                'required',
                'min:2',
                'max:10',
                Rule::unique('category_properties')->where(function ($query) use ($shop_id) {
                    if ($this->route('property')) {
                        $query = $query->where('id', '<>', $this->route('property')->id);
                    }
                    return $query->where('shop_id', $shop_id)->where('type', Property::TYPE_PARAMS)->whereNull('deleted_at');
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
