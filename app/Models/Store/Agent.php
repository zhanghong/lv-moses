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
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
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
}
