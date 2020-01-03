<?php

namespace App\Models\Shop;

use App\Models\Model;
use App\Models\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $table = 'shop_brands';

    protected $fillable = [
        'name',
        'logo_url',
        'description',
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
