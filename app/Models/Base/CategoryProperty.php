<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;

class CategoryProperty extends Model
{
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
        return $this->belongsToMany(CategorySelectors::class, 'category_property_selectors');
    }
}
