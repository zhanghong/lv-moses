<?php

namespace App\Models\Store;

use App\Models\Model;
use App\Models\Base\Area as BaseArea;

class Area extends Model
{
    protected $table = 'store_areas';

    protected $fillable = [
        'store_id',
        'province_id',
        'city_id',
        'district_id',
        'street_id',
        'path',
        'order',
    ];

    public function province()
    {
        return $this->belongsTo(BaseArea::class);
    }

    public function city()
    {
        return $this->belongsTo(BaseArea::class);
    }

    public function district()
    {
        return $this->belongsTo(BaseArea::class);
    }

    public function street()
    {
        return $this->belongsTo(BaseArea::class);
    }

    /**
     * 保存门店管辖区域
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-02-04
     * @param    array              $base_area_ids 街道区域IDs
     * @param    Store              $store         门店实例
     * @return   null
     */
    public static function saveManageStreets(array $base_area_ids, Store $store)
    {
        $streets = [];
        if (!empty($base_area_ids)) {
            $streets = BaseArea::where('level', 3)->whereIn('id', $base_area_ids)->get();
        }

        $ids = [];
        foreach ($streets as $key => $area) {
            $item = static::firstOrNew(['store_id' => $store->id, 'street_id' => $area->id]);

            $parentIds = $area->folder_ids;
            $item->province_id = $parentIds[0];
            $item->city_id = $parentIds[1];
            $item->district_id = $parentIds[2];
            $item->path = $area->path . $area->id;
            $item->order = $key + 1;
            $item->save();

            array_push($ids, $item->id);
        }

        $delete_query = static::where('store_id', $store->id);
        if (!empty($ids)) {
            $delete_query->whereNotIn('id', $ids);
        }
        $delete_query->delete();

        return ;
    }
}
