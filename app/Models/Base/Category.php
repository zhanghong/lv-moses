<?php

namespace App\Models\Base;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'icon_url',
        'is_directory',
        'level',
        'path',
        'order',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_directory' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        // 监听 Category 的创建事件，用于初始化 path 和 level 字段值
        static::saving(function (Category $category) {
            // 如果创建的是一个根类目
            if (empty($category->parent_id)) {
                // 将层级设为 0
                $category->level = 0;
                // 将 path 设为 -
                $category->path  = '-';
            } else {
                // 将层级设为父类目的层级 + 1
                $category->level = $category->parent->level + 1;
                // 将 path 值设为父类目的 path 追加父类目 ID 以及最后跟上一个 - 分隔符
                $category->path  = $category->parent->path.$category->parent_id.'-';
                // 把Parent的is_directory更新为True
                DB::table($category->getTable())
                    ->where('id', $category->parent_id)
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
}
