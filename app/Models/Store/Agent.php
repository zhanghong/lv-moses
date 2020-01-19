<?php

namespace App\Models\Store;

use App\Models\Model;
use App\Models\Shop\Shop;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use SoftDeletes;

    protected $table = 'store_agents';

    protected $fillable = [
        'name',
        'contact_name',
        'contact_phone',
        'contact_address',
        'order',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function stores()
    {
        return $this->hasMany(Stores::class);
    }

    /**
     * 允许检测值唯一是否唯一的字段列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-14
     * @return   array
     */
    protected static function allowUniqueAttrs()
    {
        return ['name'];
    }

    /**
     * 允许表单更新字段列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-17
     * @return   array
     */
    public static function parseFields() {
        return collect([
            ['name' => 'name', 'type' => 'string', 'default' => ''],
            ['name' => 'contact_name', 'type' => 'string', 'default' => ''],
            ['name' => 'contact_phone', 'type' => 'string', 'default' => ''],
            ['name' => 'contact_address', 'type' => 'string', 'default' => ''],
            ['name' => 'is_enabled', 'type' => 'boolean'],
            ['name' => 'order', 'type' => 'integer', 'default' => 0],
        ]);
    }

}
