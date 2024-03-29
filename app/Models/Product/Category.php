<?php

namespace App\Models\Product;

use App\Models\Category\Category as Model;

class Category extends Model
{
    /**
     * 模型的「启动」方法
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type_product', function (Builder $builder) {
            $builder->where('type', '=', static::TYPE_PRODUCT)->where('shop_id', '>', 0);
        });
    }


}
