<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;

class Express extends Model
{
    use SoftDeletes;

    protected $table = 'expresses';

    protected $fillable = [
        'code',
        'name',
        'logo_url',
        'order',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}
