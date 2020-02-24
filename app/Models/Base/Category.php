<?php

namespace App\Models\Base;

use DB;
use App\Models\Category\Category as Model;

use App\Models\Model;

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

        static::addGlobalScope('type_base', function (Builder $builder) {
            $builder->where('type', '=', static::TYPE_BASE)->where('shop_id', '=', 0);
        });
    }
}
