<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;

class Property extends Model
{
    public const TYPE_PARAMS = 1;
    public const TYPE_STANDARDS = 2;

    public const CHOICE_SELECT = 1;
    public const CHOICE_CHECKBOX = 2;

    use SoftDeletes;

    protected $table = 'category_properties';

    protected $fillable = [
        'name',
        'type',
        'choice',
        'order',
        'is_enabled',
    ];

    public function selectors()
    {
        return $this->hasMany(Selector::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function cat_mids()
    {
        return $this->hasMany(PropertyMid::class);
    }

    public static function findOrCreateTypeItem($type, $category_id, $name, $values, $shop_id = 0)
    {
        if ($shop_id < 1) {
            $shop_id = 0;
        }

        $query = static::where('name', $name)->where('type', $type);
        if ($shop_id) {
            $query = $query->where(function($query) use ($shop_id) {
                $query->where('shop_id', 0)->orWhere('shop_id', $shop_id);
            });
        } else {
            $query = $query->where('shop_id', 0);
        }

        $property = $query->first();
        if (empty($property)) {
            $property = new static;
            $property->shop_id = $shop_id;
            $property->name = $name;
            switch ($type) {
                case static::TYPE_STANDARDS:
                    $property->type = $type;
                    $property->choice = static::CHOICE_CHECKBOX;
                    break;
                case static::TYPE_PARAMS:
                    $property->type = $type;
                    $property->choice = static::CHOICE_SELECT;
                    break;
                default:
                    return null;
            }
            $property->is_enabled = true;
            $property->save();
        }

        $property->categories()->syncWithoutDetaching([
            $category_id => ['order' => static::ORDER_DEFAULT],
        ]);

        if ($values) {
            Selector::attachPropertyValues($property, $values, $shop_id);
        }
        return $property;
    }
}
