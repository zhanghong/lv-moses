<?php

namespace App\Models\Product;

class Detail extends Model
{
    protected $table = 'product_details';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
