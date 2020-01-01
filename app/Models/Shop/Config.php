<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User\User;

class Config extends Model
{
    use SoftDeletes;

    protected $table = 'shop_configs';

    protected $fillable = [
        'seo_keywords',
        'seo_description',
        'introduce',
        'banner_url',
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
}
