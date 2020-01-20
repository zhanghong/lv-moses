<?php

namespace App\Models\Store;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Config extends Model
{
    use SoftDeletes;

    protected $table = 'store_configs';

    protected $casts = [];

    protected $fillable = ['store_id'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * 允许表单更新字段列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-17
     * @return   Collection
     */
    public static function parseFields() {
        return collect([
            ['name' => 'store_id', 'type' => 'int'],
            ['name' => 'contact_name', 'type' => 'string', 'default' => ''],
            ['name' => 'contact_phone', 'type' => 'string', 'default' => ''],
            ['name' => 'zip_code', 'type' => 'string', 'default' => ''],
            ['name' => 'address', 'type' => 'string', 'default' => ''],
            ['name' => 'staff_count', 'type' => 'int', 'default' => 0],
        ]);
    }

    /**
     * 通过所属店铺创建或更新记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-13
     * @param    array              $params 表单数据
     * @param    Store              $store  门店实例
     * @return   Config
     */
    public static function updateOrCreateByStore(array $params, Store $store)
    {
        $config = static::firstOrNew(['store_id' => $store->id]);
        $config->parseFill($params);
        $config->save();
        return $config;
    }
}
