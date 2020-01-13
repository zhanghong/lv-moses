<?php

namespace App\Models\Shop;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;
use App\Models\User\User;
use App\Exceptions\DisallowException;

class Shop extends Model
{
    use SoftDeletes;

    // 店铺Logo图片
    public const UPLOAD_TYPE_MAIN_IMAGE = 'cover';
    // 店铺Banner图片
    public const UPLOAD_TYPE_BANNER = 'banner';

    // 店铺Logo图片最大宽度
    public const IMAGE_WIDTH_MAIN = 200;
    // 店铺Banner图片最大宽度
    public const IMAGE_WIDTH_BANNER = 1000;

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

    /**
     * 更新店铺信息
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-07
     * @param    array              $params 更新数据
     * @return   Shop
     */
    public function updateInfo(array $params)
    {
        if (!$this->is_allow_update) {
            throw new DisallowException('店铺信息不允许更新');
        }

        $fields = [
            ['name' => 'manager_id', 'type' => 'int'],
            ['name' => 'name', 'type' => 'string'],
            ['name' => 'order', 'type' => 'int', 'default' => 0],
            ['name' => 'is_enabled', 'type' => 'bool'],
        ];
        $data = static::filterFieldParams($fields, $params);

        DB::transaction(function () use ($data, $params) {
            $image = null;
            if (isset($params['main_image_id']) && $params['main_image_id']) {
                $image = Upload::shopFind($this->id, static::UPLOAD_TYPE_MAIN_IMAGE, $params['main_image_id']);
                if ($image) {
                    $data['main_image_url'] = $image->file_path;
                }
            }

            $this->update($data);
            Config::updateOrCreateByShop($params, $this);

            if ($image) {
                Upload::morphSet($this, static::UPLOAD_TYPE_MAIN_IMAGE, $image->id);
            }
        });

        return $this;
    }
}
