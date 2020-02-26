<?php

namespace App\Http\Requests\Shop\Product;

use Illuminate\Validation\Rule;
use App\Models\Category\Property;
use App\Http\Requests\Shop\FormRequest;

class StandardRequest extends FormRequest
{
    public function rules()
    {
        $shop_id = $this->currentShopId();
        return [
            'name' => [
                'required',
                'min:2',
                'max:10',
                Rule::unique('category_properties')->where(function ($query) use ($shop_id) {
                    if ($this->route('standard')) {
                        $query = $query->where('id', '<>', $this->route('standard'));
                    }
                    return $query->where('shop_id', $shop_id)->where('type', Property::TYPE_STANDARDS)->whereNull('deleted_at');
                }),
            ],
            'selectors' => [
                'required'
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '规格名',
            'selectors' => '属性值',
        ];
    }
}
