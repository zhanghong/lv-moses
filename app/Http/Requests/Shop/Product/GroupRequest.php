<?php

namespace App\Http\Requests\Shop\Product;

use Illuminate\Validation\Rule;
use App\Models\Product\Group;
use App\Http\Requests\Shop\FormRequest;

class GroupRequest extends FormRequest
{
    public function rules()
    {
        $shop_id = $this->currentShopId();

        return [
            'name' => [
                'required',
                'min:2',
                'max:20',
                Rule::unique('product_groups')->where(function ($query) use ($shop_id) {
                    $group = $this->route('group');
                    $parent_id = request('parent_id');
                    if (is_null($parent_id)) {
                        if ($group) {
                            $parent_id = $group->parent_id;
                        } else {
                            $parent_id = 0;
                        }
                    } else {
                        $parent_id = intval($parent_id);
                    }
                    $query = $query->where('parent_id', $parent_id)->where('shop_id', $shop_id);
                    if ($group) {
                        $query = $query->where('id', '<>', $group->id);
                    }
                    return $query->whereNull('deleted_at');
                }),
            ],
            'order' => [
                'integer',
                'min:0'
            ],
            'parent_id' => [
                'integer',
                'min:0',
                function ($attribute, $value, $fail) {
                    if (intval($value) > 0) {
                        $parent = Group::find($value);
                        if (!$parent || $parent->shop_id != $shop_id) {
                            return $fail('上级分组 不存在。');
                        } else if ($parent->level > 0) {
                            return $fail('店铺内只允许创建两级分组。');
                        }
                    }
                },
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '分组名',
            'parent_id' => '上级分组',
            'icon_url' => '图标',
            'order' => '排序编号',
            'is_enabled' => '是否启用',
        ];
    }
}
