<?php

namespace App\Models\Base;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;
use App\Models\User\User;

class Area extends Model
{
    use SoftDeletes;

    protected $table = 'areas';

    protected $fillable = [
        'name',
        'icon_url',
        'is_directory',
        'level',
        'path',
        'order',
        'is_enabled',
        'outer_name',
        'outer_key',
        'outer_code',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_directory' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        // 监听 Area 的创建事件，用于初始化 path 和 level 字段值
        static::saving(function (Area $area) {
            $parent = $area->parent;
            // 如果创建的是一个根类目
            if (empty($parent)) {
                // 将层级设为 0
                $area->level = 0;
                // 将 path 设为 -
                $area->path  = '-';
            } else {
                // 将层级设为父类目的层级 + 1
                $area->level = $parent->level + 1;
                // 将 path 值设为父类目的 path 追加父类目 ID 以及最后跟上一个 - 分隔符
                $area->path  = $parent->path.$parent->id.'-';
                // 把Parent的is_directory更新为True
                DB::table($parent->getTable())
                    ->where('id', $parent->id)
                    ->where('is_directory', 0)
                    ->update(['is_directory' => 1]);
            }
        });
    }

    public function editor()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Area::class);
    }

    public function children()
    {
        return $this->hasMany(Area::class, 'parent_id');
    }

    // 获取所有祖先类目的 ID 值
    public function getPathIdsAttribute()
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
            ->whereIn('id', $this->path_ids)
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
                    ->implode('-'); // 用 - 符号将数组的值组装成一个字符串
    }
}
