<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    public const TYPE_NORMAL = 'normal';

    protected $table = 'product_brands';

    protected $casts = [

    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Category::class);
    }

    public function detail()
    {
        return $this->hasOne(Detail::class);
    }

    public function skus()
    {
        return $this->hasMany(Sku::class);
    }
}
