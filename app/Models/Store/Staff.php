<?php

namespace App\Models\Store;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

    protected $table = 'store_staffs';

    protected $fillable = [
        'user_id',
        'role_id',
    ];

    protected $casts = [];
}
