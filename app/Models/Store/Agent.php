<?php

namespace App\Models\Store;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use SoftDeletes;

    protected $table = 'store_agents';

    protected $fillable = [
        'manager_id',
        'title',
        'contact_name',
        'contact_phone',
    ];

    protected $casts = [
    ];
}
