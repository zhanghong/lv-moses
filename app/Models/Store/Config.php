<?php

namespace App\Models\Store;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Config extends Model
{
    use SoftDeletes;

    protected $table = 'store_configs';

    protected $fillable = [
        'seller_id',
        'auth_no',
        'contact_name',
        'contact_phone',
        'province',
        'city',
        'district',
        'address',
        'zip',
        'full_address',
        'staff_count',
    ];

    protected $casts = [
        'full_address' => 'json',
    ];
}
