<?php

namespace App\Models\Store;

use App\Models\Model;

class Area extends Model
{
    protected $table = 'store_areas';

    protected $fillable = [
        'province',
        'city',
        'district',
        'path',
        'order',
    ];

    protected $casts = [
        'full_address' => 'json',
    ];
}
