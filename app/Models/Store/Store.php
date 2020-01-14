<?php

namespace App\Models\Store;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use SoftDeletes;

    protected $table = 'stores';

    protected $fillable = [
        'manager_id',
        'title',
        'main_image_url',
        'order',
        'longitude',
        'latitude',
        'is_enabled',
        'work_start_time',
        'work_end_time',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}
