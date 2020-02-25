<?php

namespace App\Models\Category;

use App\Models\Category\Property;
use Illuminate\Database\Eloquent\Builder;

class StandardProperty extends Property
{
    /**
     * 模型的「启动」方法
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type_standard', function (Builder $builder) {
            $builder->where('type', '=', static::TYPE_STANDARDS);
        });
    }
}
