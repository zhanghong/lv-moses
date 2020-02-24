<?php

namespace App\Models\Product;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $table = 'product_groups';

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_directory' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        // 监听 Category 的创建事件，用于初始化 path 和 level 字段值
        static::saving(function (Category $category) {
            $parent = $category->parent;
            // 如果创建的是一个根类目
            if (empty($parent)) {
                // 将层级设为 0
                $category->level = 0;
                // 将 path 设为 -
                $category->path  = '-';
            } else {
                // 将层级设为父类目的层级 + 1
                $category->level = $parent->level + 1;
                // 将 path 值设为父类目的 path 追加父类目 ID 以及最后跟上一个 - 分隔符
                $category->path  = $parent->path.$parent->id.'-';
                // 把Parent的is_directory更新为True
                DB::table($category->getTable())
                    ->where('id', $parent->id)
                    ->where('is_directory', 0)
                    ->update(['is_directory' => 1]);
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(Category::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
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
            ['name' => 'icon_url', 'type' => 'string', 'default' => ''],
            ['name' => 'parent_id', 'type' => 'integer', 'default' => 0],
            ['name' => 'is_enabled', 'type' => 'boolean'],
            ['name' => 'order', 'type' => 'integer', 'default' => 0],
        ]);
    }
}
