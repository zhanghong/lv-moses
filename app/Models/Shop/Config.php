<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;
use App\Models\User\User;

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
     * 通过所属店铺创建或更新记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-13
     * @param    array              $params 表单数据
     * @param    Shop               $shop   店铺实例
     * @return   Config
     */
    public static function updateOrCreateByShop(array $params, Shop $shop)
    {
        $fields = [
            ['name' => 'seo_keywords', 'type' => 'string', 'default' => ''],
            ['name' => 'seo_description', 'type' => 'string', 'default' => ''],
            ['name' => 'introduce', 'type' => 'string', 'default' => ''],
        ];
        $data = static::filterFieldParams($fields, $params);

        if (isset($params['banner_id']) && $params['banner_id']) {
            $image = Upload::shopFind($shop->id, Shop::UPLOAD_TYPE_BANNER, $params['banner_id']);
            if ($image) {
                $data['banner_url'] = $image->file_path;
                Upload::morphSet($shop, Shop::UPLOAD_TYPE_BANNER, $image->id);
            }
        }

        return static::updateOrCreate(['shop_id' => $shop->id], $data);
    }
}
