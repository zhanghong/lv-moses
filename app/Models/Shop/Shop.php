<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;
use App\Models\User\User;

class Shop extends Model
{
    use SoftDeletes;

    protected $table = 'shops';

    protected $fillable = [
        'manager_id',
        'name',
        'main_image_url',
        'store_count',
        'order',
        'is_enabled',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_enabled' => 'boolean',
    ];

    public function creater()
    {
        return $this->belongsTo(User::class);
    }

    public function editor()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class);
    }

    public function config()
    {
        return $this->hasOne(Config::class);
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * 创建默认店铺
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-01
     * @param    array              $params  店铺信息
     * @param    User               $manager 负责用户
     * @return   bool
     */
    public static function createDefault(array $params, User $manager): bool
    {
        if (static::where('is_default', true)->count())
        {
            return false;
        }else if (empty($params)) {
            return false;
        } else if (empty($manager)) {
            return false;
        }

        if (isset($params['name'])) {
            $name = $params['name'];
            unset($params['name']);
        } else {
            $name = '默认店铺';
        }
        $shop = static::create(['name' => $name, 'is_default' => true]);
        $shop->creater()->associate($manager);
        $shop->editor()->associate($manager);
        $shop->manager()->associate($manager);
        $shop->save();

        $configData = [];
        $configFields = ['seo_keywords', 'seo_description', 'introduce'];
        foreach ($configFields as $key => $name) {
            if (isset($params[$name])) {
                $configData[$name] = $params[$name];
            } else {
                $configData[$name] = '';
            }
        }
        Config::updateOrCreate(['shop_id' => $shop->id], $configData);

        return true;
    }
}
