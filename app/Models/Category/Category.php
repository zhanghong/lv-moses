<?php

namespace App\Models\Category;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;

class Category extends Model
{
    public const TYPE_BASE = 1;
    public const TYPE_PRODUCT = 2;

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
            ['name' => 'is_directory', 'type' => 'boolean'],
            ['name' => 'level', 'type' => 'integer', 'default' => 0],
            ['name' => 'path', 'type' => 'string', 'default' => ''],
            ['name' => 'parent_id', 'type' => 'integer', 'default' => 0],
            ['name' => 'is_enabled', 'type' => 'boolean'],
            ['name' => 'order', 'type' => 'integer', 'default' => 0],
        ]);
    }

    protected function parent()
    {
        return $this->belongsTo(Category::class);
    }

    protected function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function properties()
    {
        return $this->belongsToMany(Property::class)->withTimestamps();
    }

    // 获取所有祖先类目的 ID 值
    public function getFolderIdsAttribute()
    {
        // trim($str, '-') 将字符串两端的 - 符号去除
        // explode() 将字符串以 - 为分隔切割为数组
        // 最后 array_filter 将数组中的空值移除
        return array_filter(explode('-', trim($this->path, '-')));
    }

    // 获取所有祖先类目并按层级排序
    public function getAncestorsAttribute()
    {
        return static::query()
            // 使用上面的访问器获取所有祖先类目 ID
            ->whereIn('id', $this->folder_ids)
            // 按层级排序
            ->orderBy('level')
            ->get();
    }

    // 获取以 - 为分隔的所有祖先类目名称以及当前类目的名称
    public function getFullNameAttribute()
    {
        return $this->ancestors  // 获取所有祖先类目
                    ->pluck('name') // 取出所有祖先类目的 name 字段作为一个数组
                    ->push($this->name) // 将当前类目的 name 字段值加到数组的末尾
                    ->implode(' > '); // 用 > 符号将数组的值组装成一个字符串
    }

    public static function selectByParentId(int $parent_id)
    {
        return static::withOrder('ASC')
                    ->where('parent_id', $parent_id)
                    ->get();
    }
}
