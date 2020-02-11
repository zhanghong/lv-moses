<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\SoftDeletes;

class Sku extends Model
{
    use SoftDeletes;

    protected $table = 'product_skus';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function properties()
    {
        return $this->hasMany(SkuProperty::class);
    }
}
