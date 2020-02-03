<?php

namespace App\Models\Base;

use App\Models\Model;

class CategoryPropertySelector extends Model
{
    protected $table = 'category_property_selectors';

    protected $fillable = [
        'property_id',
        'selector_id',
        'order',
    ];
}
