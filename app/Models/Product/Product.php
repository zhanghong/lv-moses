<?php

namespace App\Models\Product;

use DB;
use App\Models\Base\Upload;
use App\Exceptions\DisallowException;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    // 商品类型
    public const TYPE_NORMAL = 'normal';

    // 上传附件类型-商品图片
    public const UPLOAD_TYPE_IMAGE_MAIN = 'product_main';
    // 上传附件类型-商品介绍图片
    public const UPLOAD_TYPE_IMAGE_DESC = 'product_desc';

    // 上传图片验证
    public const IMAGE_MAIN_MIN_WIDTH = 1242;
    public const IMAGE_MAIN_MIN_HEIGHT = 698;
    public const IMAGE_MAIN_MAX_SIZE = 1024; //1024kb
    public const IMAGE_DESC_MIN_WIDTH = 400;
    public const IMAGE_DESC_MAX_HEIGHT = 1000;
    public const IMAGE_DESC_MAX_SIZE = 1024;

    protected $casts = [

    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Category::class);
    }

    public function detail()
    {
        return $this->hasOne(Detail::class);
    }

    public function skus()
    {
        return $this->hasMany(Sku::class);
    }

    /**
     * 允许表单更新字段列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-17
     * @return   array
     */
    public static function parseFields() {
        return collect([
            ['name' => 'base_category_id', 'type' => 'int'],
            ['name' => 'category_id', 'type' => 'int'],
            ['name' => 'type', 'type' => 'string', 'default' => static::TYPE_NORMAL],
            ['name' => 'title', 'type' => 'string', 'default' => ''],
            ['name' => 'long_title', 'type' => 'string', 'default' => ''],
            ['name' => 'description', 'type' => 'string'],
            ['name' => 'fiction_count', 'type' => 'int', 'default' => 0],
            ['name' => 'order', 'type' => 'integer'],
        ]);
    }

    /**
     * 更新门店信息
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-02-11
     * @param    array              $params 更新数据
     * @return   Product
     */
    public function updateInfo(array $params)
    {
        if (!$this->is_allow_update) {
            throw new DisallowException('店铺信息不允许更新');
        }
        $this->parseFill($params);

        $flags = 0;
        $flag_fields = ['is_sell', 'is_new', 'is_hot', 'is_best'];
        foreach ($flag_fields as $idx => $name) {
            $value = $params[$name] ?? 0 ;
            if ($value) {
                $flag += $idx * 2 + 1;
            }
        }
        $this->flags = $flags;
        // DB::transaction(function () use ($params) {
            $this->save();

            $first_main_image = null;
            $main_image_ids = $params['main_image_ids'] ?? [];
            if ($main_image_ids) {
                Upload::ownSet($this->shop, static::UPLOAD_TYPE_IMAGE_MAIN, $this, $main_image_ids);
                $first_main_image = Upload::ownFirst($this->shop, static::UPLOAD_TYPE_IMAGE_MAIN, $this, ['order' => 'ASC']);
            }
            // 商品主图片
            if ($first_main_image) {
                $this->main_image_url = $first_main_image->file_path;
                $params['main_image_id'] = $first_main_image->id;
            } else {
                $this->main_image_url = '';
                $params['main_image_id'] = 0;
            }
            $this->save();

            // 保存商品介绍图片
            $desc_image_ids = $params['desc_image_ids'] ?? [];
            Upload::ownSet($this->shop, static::UPLOAD_TYPE_IMAGE_DESC, $this, $desc_image_ids);

            // 保存商品详情
            Detail::updateOrCreateByProduct($params, $this);

            $param_skus = $params['skus'] ?? [];
            // 同步更新商品的SKU
            Sku::syncByProduct($param_skus, $this);
        // });
        return $this;
    }
}
