<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class SkuProperty extends Model
{
    protected $table = 'sku_properties';

    public function sku()
    {
        return $this->belongsTo(Sku::class);
    }
}
