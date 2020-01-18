<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;
use App\Models\User\User;
use App\Models\Base\Upload;

class Config extends Model
{
    use SoftDeletes;

    protected $table = 'shop_configs';

    protected $fillable = [
        'seo_keywords',
        'seo_description',
        'introduce',
        'banner_url',
    ];

    protected $casts = [
    ];

    public function editor()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * 允许表单更新字段列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-17
     * @return   array
     */
    public static function parseFields() {
        return collect([
            ['name' => 'seo_keywords', 'type' => 'string', 'default' => ''],
            ['name' => 'seo_description', 'type' => 'string', 'default' => ''],
            ['name' => 'introduce', 'type' => 'string', 'default' => ''],
        ]);
    }

    /**
     * 通过所属店铺创建或更新记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-13
     * @param    array              $params 表单数据
     * @param    Shop               $shop   店铺实例
     * @return   Config
     */
    public static function updateOrCreateByShop(array $params, Shop $shop)
    {

        $config = static::firstOrNew(['shop_id' => $shop->id]);
        $config->parseFill($params);

        $image = null;
        if (isset($params['banner_id'])) {
            $image = Upload::ownFirst($shop, Shop::UPLOAD_TYPE_BANNER, null, ['ids' => $params['banner_id']]);
        }
        if ($image) {
            $config->banner_url = $image->file_path;
        }

        $config->save();
        // Upload更新一定要在save方法之后，否则可能config->id is null
        if ($image) {
            Upload::ownSet($shop, Shop::UPLOAD_TYPE_BANNER, $config, $image->id);
        }

        return $config;
    }
}
