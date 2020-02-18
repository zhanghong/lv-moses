<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\FormRequest;

class ProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'base_category_id' => [
                'required',
                'integer',
                'gt:0',
            ],
            'category_id' => [
                'required',
                'integer',
                'gt:0',
            ],
            'title' => [
                'required',
                'min:5',
                'max:50',
            ],
            'long_title' => [
                'nullable',
                'max:200',
            ],
            'main_image_ids' => [
                'required',
            ],
            'fiction_count' => [
                'integer',
                'gte:0',
            ],
            'skus.*.image_ids' => [
                'required',
            ],
            'skus.*.sell_price' => [
                'required',
                'gt:0',
            ],
            'skus.*.stock' => [
                'required',
                'integer',
                'gt:0',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'base_category_id' => '商品类目',
            'category_id' => '商品分类',
            'title' => '标题',
            'long_title' => '长标题',
            'main_image_ids' => '商品图片',
            'fiction_count' => '已售数量',
            'skus.*.image_ids' => 'SKU图片',
            'skus.*.sell_price' => 'SKU售价',
            'skus.*.stock' => 'SKU库存',
        ];
    }
}
