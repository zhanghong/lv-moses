<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;

class Property extends Model
{
    public const TYPE_STANDARDS = '2';
    public const TYPE_PARAMS = '1';

    public const CHOICE_SELECT = '1';
    public const CHOICE_CHECKBOX = '2';

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
}
