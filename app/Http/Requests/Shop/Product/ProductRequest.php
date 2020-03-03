<?php

namespace App\Http\Requests\Shop\Product;

use App\Models\Product\Product;
use App\Exceptions\LogicException;
use App\Http\Requests\Shop\FormRequest;

class ProductRequest extends FormRequest
{
    public function rules()
    {
        $shop_id = $this->currentShopId();
        $skus = $this->route('skus');
        return [
            'brand_id' => [
                'required',
                'integer',
                'gt:0',
            ],
            'group_id' => [
                'required',
                'integer',
                'gt:0',
            ],
            'title' => [
                'required',
                'min:5',
                'max:50',
                function ($attribute, $value, $fail) use ($shop_id) {
                    $wheres = [['shop_id', '=', $shop_id]];

                    if ($this->route('id')) {
                        $wheres[] = ['id', '<>', $this->route('id')];
                    }
                    try {
                        Product::checkAttrUnique('title', $value, $wheres);
                    } catch (LogicException $e) {
                        $fail('商品 已经存在。');
                    }
                },
            ],
            'long_title' => [
                'nullable',
                'max:200',
            ],
            'image_ids' => [
                'required',
            ],
            'description' => [
                'min:5',
                'max:2000',
            ],
            'fiction_count' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'sell_price' => [
                function ($attribute, $value, $fail) use ($skus) {
                    if (empty($skus)) {
                        if ($value == '') {
                            $fail('现价 不能为空。');
                        } else if ($value < 0) {
                            $fail('现价 不能低于0元。');
                        }
                    }
                },
            ],
            'stock' => [
                function ($attribute, $value, $fail) use ($skus) {
                    if (empty($skus)) {
                        if ($value == '') {
                            $fail('库存 不能为空。');
                        } else if ($value < 0) {
                            $fail('库存 不能小于0。');
                        }
                    }
                },
            ],
            // 'skus.*.image_ids' => [
            //     'required',
            // ],
            'skus.*.sell_price' => [
                'required',
                'gte:0',
            ],
            'skus.*.stock' => [
                'required',
                'integer',
                'gte:0',
            ],
        ];
    }

    public function attributes()
    {
        return [
            // 'base_category_id' => '商品类目',
            'brand_id' => '商品品牌',
            'group_id' => '商品分组',
            'title' => '标题',
            'long_title' => '长标题',
            'image_ids' => '商品图片',
            'fiction_count' => '已售数量',
            'skus.*.images' => 'SKU图片',
            'skus.*.sell_price' => 'SKU售价',
            'skus.*.stock' => 'SKU库存',
        ];
    }
}
