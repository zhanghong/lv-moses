<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $table = 'product_brands';

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

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
            ['name' => 'logo_url', 'type' => 'string', 'default' => ''],
            ['name' => 'description', 'type' => 'string', 'default' => ''],
            ['name' => 'is_enabled', 'type' => 'boolean'],
            ['name' => 'order', 'type' => 'integer', 'default' => 0],
        ]);
    }
}
