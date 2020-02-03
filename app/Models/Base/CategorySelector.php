<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;

class CategorySelector extends Model
{
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
}
