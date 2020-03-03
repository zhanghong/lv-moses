<?php

namespace App\Models\Product;

use DB;
use Carbon\Carbon;
use App\Models\Base\Upload;
use App\Models\Category\Property;
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
    public const IMAGE_MAIN_MAX_SIZE = 5120; //1024kb
    public const IMAGE_DESC_MIN_WIDTH = 400;
    public const IMAGE_DESC_MAX_HEIGHT = 1000;
    public const IMAGE_DESC_MAX_SIZE = 5120;

    protected $casts = [
        'is_published' => 'boolean',
        'min_market_price' => 'decimal:2',
        'max_market_price' => 'decimal:2',
        'min_sell_price' => 'decimal:2',
        'max_sell_price' => 'decimal:2',
    ];

    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
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
     * 允许检测值唯一是否唯一的字段列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-14
     * @return   array
     */
    protected static function allowUniqueAttrs()
    {
        return ['title'];
    }

    /**
     * 允许表单更新字段列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-17
     * @return   array
     */
    public static function parseFields() {
        return collect([
            ['name' => 'brand_id', 'type' => 'int'],
            ['name' => 'group_id', 'type' => 'int'],
            ['name' => 'type', 'type' => 'string', 'default' => static::TYPE_NORMAL],
            ['name' => 'title', 'type' => 'string', 'default' => ''],
            ['name' => 'long_title', 'type' => 'string', 'default' => ''],
            ['name' => 'description', 'type' => 'string'],
            ['name' => 'fiction_count', 'type' => 'int', 'default' => 0],
            ['name' => 'is_published', 'type' => 'boolean', 'default' => false],
            ['name' => 'order', 'type' => 'integer'],
        ]);
    }

    public function getMarketPriceRangeAttribute()
    {
        if ($this->min_market_price > 0 && $this->max_market_price > 0) {
            return $this->min_market_price . '~' . $this->max_market_price;
        } else if ($this->min_market_price > 0) {
            return $this->min_market_price . '~';
        } else if ($this->max_market_price > 0) {
            return '~' . $this->max_market_price;
        } else {
            return '--';
        }
    }

    public function getSellPriceRangeAttribute()
    {
        if ($this->min_sell_price > 0 && $this->max_sell_price > 0) {
            return $this->min_sell_price . '~' . $this->max_sell_price;
        } else if ($this->min_sell_price > 0) {
            return $this->min_sell_price . '~';
        } else if ($this->max_sell_price > 0) {
            return '~' . $this->max_sell_price;
        } else {
            return '--';
        }
    }

    public function getMainImagesAttribute()
    {
        return Upload::ownGet($this->shop, static::UPLOAD_TYPE_IMAGE_MAIN, $this);
    }

    public function getDescImagesAttribute()
    {
        return Upload::ownGet($this->shop, static::UPLOAD_TYPE_IMAGE_DESC, $this);
    }

    public function getChoicedSelectorsAttribute()
    {
        return SkuProperty::productChoicedProperties($this->id);
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

        $dirty = $this->getDirty();
        if (!$this->id || $this->wasChanged('is_published')) {
            if (!$this->is_published) {
                $this->published_at = null;
            } else {
                $this->published_at = Carbon::now()->toDateTimeString();
            }
        }

        DB::transaction(function () use ($params) {
            $this->save();

            $first_main_image = null;
            $main_image_ids = $params['image_ids'] ?? [];
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
            if (empty($param_skus)) {
                $param_skus = [
                    [
                        'market_price' => $params['market_price'] ?? 0,
                        'sell_price' => $params['sell_price'] ?? 0,
                        'stock' => $params['stock'] ?? 0,
                        'selector_ids' => '',
                    ]
                ];
            }
            // 同步更新商品的SKU
            Sku::syncByProduct($param_skus, $this);
            $this->updateSkuRange();
        });
        return $this;
    }

    /**
     * 更新Sku概况统计信息
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-03-03
     * @return   [type]             [description]
     */
    public function updateSkuRange()
    {
        $selects = [
            DB::raw('MAX(sell_price) AS max_sell_price'),
            DB::raw('MIN(sell_price) AS min_sell_price'),
            DB::raw('MAX(market_price) AS max_market_price'),
            DB::raw('MAX(market_price) AS min_market_price'),
            DB::raw("SUM(IF(selector_ids='', 1, 0)) AS default_count"),
        ];
        $item = Sku::select($selects)
                    ->where('product_id', $this->id)
                    ->first();
        if (empty($item)) {
            $this->max_sell_price = 0;
            $this->min_sell_price = 0;
            $this->max_market_price = 0;
            $this->min_market_price = 0;
            $this->stock = 0;
            $this->has_skus = false;
        } else {
            $this->max_sell_price = $item->max_sell_price;
            $this->min_sell_price = $item->min_sell_price;
            $this->max_market_price = $item->max_market_price;
            $this->min_market_price = $item->min_market_price;
            $this->has_skus = ($item->default_count > 0) ? false : true ;

            $min = Sku::where('product_id', $this->id)->orderBy('sell_price', 'ASC')->first();
            $this->stock = $min->stock;
        }

        $this->save();
    }
}
