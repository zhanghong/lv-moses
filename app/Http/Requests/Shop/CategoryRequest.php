<?php

namespace App\Http\Requests\Shop;

use Illuminate\Validation\Rule;
use App\Http\Requests\FormRequest;

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
                    $parent_id = $this->route('parent_id');
                    if ($parent_id === '') {
                        if ($category) {
                            $parent_id = $category->parent_id;
                        } else {
                            $parent_id = 0;
                        }
                    }
                    if ($category) {
                        $query = $query->where('id', '<>', $category->id);
                    }
                    return $query->where('parent_id', $parent_id)->where('shop_id', $this->route('shop')->id)->whereNull('deleted_at');
                }),
            ],
            'base_category_id' => ['required', 'min:1'],
            'order' => ['integer', 'min:0'],
            'parent_id' => ['min:0'],
        ];
    }
}
