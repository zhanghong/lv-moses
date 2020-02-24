<?php

namespace App\Http\Requests\Product;

use Illuminate\Validation\Rule;
use App\Models\Product\Category;
use App\Http\Requests\Shop\FormRequest;

class CategoryRequest extends FormRequest
{
    public function rules()
    {
        $shop_id = $this->currentShopId();

        return [
            'name' => [
                'required',
                'min:2',
                'max:20',
                Rule::unique('product_categories')->where(function ($query) use ($shop_id) {
                    $category = $this->route('category');
                    $parent_id = request('parent_id');
                    if (is_null($parent_id)) {
                        if ($category) {
                            $parent_id = $category->parent_id;
                        } else {
                            $parent_id = 0;
                        }
                    } else {
                        $parent_id = intval($parent_id);
                    }
                    $query = $query->where('parent_id', $parent_id)->where('shop_id', $shop_id);
                    if ($category) {
                        $query = $query->where('id', '<>', $category->id);
                    }
                    return $query->whereNull('deleted_at');
                }),
            ],
            'order' => ['integer', 'min:0'],
            'parent_id' => [
                'integer',
                'min:0',
                function ($attribute, $value, $fail) {
                    if (intval($value) > 0) {
                        $parent = Category::find($value);
                        if (!$parent || $parent->shop_id != $shop_id) {
                            return $fail('上级分类不存在');
                        } else if ($parent->level > 0) {
                            return $fail('店铺内只允许创建两分分类');
                        }
                    }
                },
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '分类名',
            'parent_id' => '上级分类',
            'icon_url' => '图标',
            'order' => '排序编号',
            'is_enabled' => '是否启用',
        ];
    }
}
