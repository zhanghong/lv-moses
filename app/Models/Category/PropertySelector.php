<?php

namespace App\Models\Category;

use App\Models\Model;

class PropertySelector extends Model
{
    protected $table = 'category_property_selectors';

    protected $fillable = [
        'property_id',
        'selector_id',
        'order',
    ];
}
