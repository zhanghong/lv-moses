<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;
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

    public static function updateOrCreateByShop($params, Shop $shop)
    {
        $fields = [
            ['name' => 'seo_keywords', 'type' => 'string', 'default' => ''],
            ['name' => 'seo_description', 'type' => 'string', 'default' => ''],
            ['name' => 'introduce', 'type' => 'string', 'default' => ''],
            ['name' => 'banner_url', 'type' => 'string', 'default' => ''],
        ];
        $data = static::filterFieldParams($fields, $params);
        return static::updateOrCreate(['shop_id' => $shop->id], $data);
    }
}
