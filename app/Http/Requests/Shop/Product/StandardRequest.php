<?php

namespace App\Http\Requests\Shop\Product;

use Illuminate\Validation\Rule;
use App\Models\Category\Category;
use App\Models\Category\Property;
use App\Http\Requests\Shop\FormRequest;

class StandardRequest extends FormRequest
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
                    return $query->where('shop_id', $shop_id)->where('type', Property::TYPE_STANDARDS)->whereNull('deleted_at');
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
