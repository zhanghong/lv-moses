<?php

namespace App\Models\Store;

use DB;
use App\Models\Model;
use App\Models\User\User;
use App\Models\Shop\Shop;
use App\Models\Base\Area as BaseArea;
use App\Models\Base\Upload;
use App\Models\Store\Area as StoreArea;
use App\Exceptions\DisallowException;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    // 店铺Logo图片
    public const UPLOAD_TYPE_INTRO = 'store_intro';
    public const IMAGE_WIDTH_INTRO = 1000;

    use SoftDeletes;

    protected $table = 'stores';

    protected $fillable = [];

    protected $casts = [
        'is_enabled' => 'boolean',
        'full_address' => 'json',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class);
    }

    public function locate_area()
    {
        return $this->belongsTo(BaseArea::class, 'area_id');
    }

    public function region_areas()
    {
        return $this->hasMany(StoreArea::class);
    }

    public function config()
    {
        return $this->hasOne(Config::class);
    }

    public function images()
    {
        return $this->morphMany(Upload::class, 'attachable')->where('attach_type', static::UPLOAD_TYPE_INTRO);
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
            ['name' => 'agent_id', 'type' => 'int', 'default' => 0],
            ['name' => 'manager_id', 'type' => 'int', 'default' => 0],
            ['name' => 'area_id', 'type' => 'int', 'default' => 0],
            ['name' => 'name', 'type' => 'string', 'default' => ''],
            ['name' => 'auth_no', 'type' => 'string', 'default' => ''],
            ['name' => 'longitude', 'type' => 'float'],
            ['name' => 'latitude', 'type' => 'float'],
            ['name' => 'work_start_time', 'type' => 'time'],
            ['name' => 'work_end_time', 'type' => 'time'],
            ['name' => 'is_enabled', 'type' => 'boolean'],
            ['name' => 'order', 'type' => 'integer', 'default' => static::ORDER_DEFAULT],
        ]);
    }

    public function getFullAddressStrAttribute()
    {
        if (!$this->full_address) {
            return '';
        }

        $values = [];
        $names = ['areas_name', 'address'];
        foreach ($names as $key => $name) {
            $values[] = $this->full_address[$name] ?? '';
        }
        return implode(' ', $values);
    }

    /**
     * 更新门店信息
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-18
     * @param    array              $params 更新数据
     * @return   Shop
     */
    public function updateInfo(array $params)
    {
        if (!$this->is_allow_update) {
            throw new DisallowException('店铺信息不允许更新');
        }
        $this->parseFill($params);

        DB::transaction(function () use ($params) {
            if ($this->locate_area) {
                $json = [
                    'areas_name' => $this->locate_area->full_name,
                    'address' => $params['address'] ?? '',
                ];
            } else {
                $json = [
                    'areas_name' => '',
                    'address' => '',
                ];
            }
            $this->full_address = $json;
            $this->save();

            StoreArea::saveManageStreets($params['street_ids'], $this);

            Config::updateOrCreateByStore($params, $this);

            // 只有参数 image_ids 存在并是数组时才更新关联图片记录
            if (isset($params['image_ids']) && is_array($params['image_ids'])) {
                Upload::ownSet($this->shop, static::UPLOAD_TYPE_INTRO, $this, $params['image_ids']);

                $first = Upload::ownFirst($this->shop, static::UPLOAD_TYPE_INTRO, $this, ['order' => 'ASC']);
                if ($first) {
                    $this->main_image_id = $first->id;
                    $this->main_image_url = $first->file_path;
                } else {
                    $this->main_image_id = 0;
                    $this->main_image_url = '';
                }

                if ($this->isDirty()) {
                    // 只有当主图片更新时调用save方法
                    $this->save();
                }
            }
        });
        return $this;
    }
}
