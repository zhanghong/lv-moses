<?php

namespace App\Http\Requests\Shop;

use App\Models\Shop\Shop;
use App\Http\Requests\FormRequest;
use App\Exceptions\LogicException;

class ConfigRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'min:2',
                'max:20',
                function ($attribute, $value, $fail) {
                    $wheres = [];
                    if ($this->route('shop')) {
                        $wheres[] = ['id','<>', $this->route('shop')->id];
                    }
                    try {
                        Shop::checkAttrUnique('name', $value, $wheres);
                    } catch (LogicException $e) {
                        $fail('店铺名 已经存在。');
                    }
                },
            ],
            'order' => ['sometimes', 'integer', 'min:0'],
            'seo_keywords' => ['max:50'],
            'seo_description' => ['max:200'],
            'introduce' => ['max:200'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '店铺名',
            'main_image_url' => '主图片',
            'order' => '排序编号',
            'is_enabled' => '是否启用',
            'seo_keywords' => 'SEO关键词',
            'seo_description' => 'SEO简介',
            'introduce' => '店铺介绍',
            'banner_url' => 'Banner 图片',
        ];
    }
}
