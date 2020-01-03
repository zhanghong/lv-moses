<?php

namespace App\Http\Requests\Shop;

use Illuminate\Validation\Rule;
use App\Http\Requests\FormRequest;
use App\Models\Shop\Category;
use App\Models\Base\Category as BaseCategory;

class CategoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'min:2',
                'max:20',
                Rule::unique('shop_categories')->where(function ($query) {
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
                    if ($category) {
                        $query = $query->where('id', '<>', $category->id);
                    }
                    return $query->where('parent_id', '=', $parent_id)->where('shop_id', $this->route('shop')->id)->whereNull('deleted_at');
                }),
            ],
            'base_category_id' => [
                'required',
                'min:1',
                function ($attribute, $value, $fail) {
                    $base = BaseCategory::find($value);
                    if (!$base) {
                        return $fail('系统分类不存在');
                    } else if ($base->is_directory) {
                        return $fail('系统分类必须是三级子类目');
                    }
                },
            ],
            'order' => ['integer', 'min:0'],
            'parent_id' => [
                'integer',
                'min:0',
                function ($attribute, $value, $fail) {
                    if (intval($value) > 0) {
                        $parent = Category::find($value);
                        if (!$parent || $parent->shop_id != $this->route('shop')->id) {
                            return $fail('上级分类不存在');
                        } else if ($parent->level > 0) {
                            return $fail('店铺内只允许创建两分分类');
                        }
                    }
                },
            ],
        ];
    }
}
