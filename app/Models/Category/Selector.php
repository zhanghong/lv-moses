<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;

class Selector extends Model
{
    public const ORDER_DEFAULT = 999;

    use SoftDeletes;

    protected $table = 'category_selectors';

    protected $fillable = [
        'name',
        'order',
        'is_enabled',
    ];

    public function properties()
    {
        return $this->belongsToMany(CategoryProperty::class, 'category_property_selectors');
    }

    /**
     * 追加属性值
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-02-10
     * @param    int/Property       $property 属性ID/实例
     * @param    string/array       $values   属性值
     * @param    int                $shop_id  店铺ID
     * @return   bool
     */
    public static function attachPropertyValues($property, $values, $shop_id = null)
    {
        if (is_int($property)) {
            $property = Property::find($property);
        }

        if (empty($property)) {
            return false;
        }

        if (is_null($shop_id)) {
            $shop_id = 0;
        }

        if (!in_array($property->shop_id, [0, $shop_id])) {
            return false;
        }

        if (!is_array($values)) {
            $values = explode(',', $values);
        }

        foreach ($values as $key => $name) {
            $item = static::findOrCreateItem($property->id, $name, $shop_id);
        }

        $property->value_ids = static::where('property_id', $property->id)
                    ->get()->map(function($item) {
                        return $item->id;
                    })->join(',');
        $property->save();

        return true;
    }

    /**
     * 创建属性值记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-02-10
     * @param    int                $property_id 属性ID
     * @param    string             $name        名称
     * @param    int                $shop_id     店铺ID
     * @return   Selector
     */
    private static function findOrCreateItem(int $property_id, string $name, int $shop_id)
    {
        $name = trim($name);
        if (empty($name)) {
            return null;
        }

        $query = static::where('name', $name)->where('property_id', $property_id);
        if ($shop_id > 0) {
            $query = $query->where(function($query) use ($shop_id) {
                $query->where('shop_id', 0)->orWhere('shop_id', $shop_id);
            });
        } else {
            $query = $query->where('shop_id', 0);
        }

        $item = $query->first();
        if (empty($item)) {
            $item = new static;
            $item->shop_id = $shop_id;
            $item->property_id =  $property_id;
            $item->name = $name;
            $item->order = static::ORDER_DEFAULT;
            $item->is_enabled = true;
            $item->save();
        }

        return $item;
    }
}
