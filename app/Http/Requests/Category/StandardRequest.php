<?php

namespace App\Http\Requests\Category;

use Illuminate\Validation\Rule;
use App\Models\Category\Category;
use App\Http\Requests\FormRequest;

class StandardRequest extends FormRequest
{
    public function rules()
    {
        return [
            'category_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $query = Category::where('id', $value)->where('level', 3);
                    if (!$query->count()){
                        $fail('所属分类 必须是第三级类目。');
                    }
                },
            ],
            'name' => [
                'required',
                'min:2',
                'max:10',
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
            'category_id' => '所属类目',
            'name' => '规格名',
            'values' => '规格值',
        ];
    }
}
