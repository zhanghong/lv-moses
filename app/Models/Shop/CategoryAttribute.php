<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

use App\Models\User\User;

class CategoryAttribute extends Model
{
    protected $table = 'shop_category_attributes';

    protected $fillable = [
        'name',
        'type',
        'values',
    ];

    protected $casts = [
    ];

    public function editor()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
