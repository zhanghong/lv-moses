<?php

namespace App\Models\Product;

use App\Models\Model as BaseModel;
use App\Models\Shop\Shop;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends BaseModel
{
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
