<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Model;
use App\Models\User\User;

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

    public function creater()
    {
        return $this->belongsTo(User::class);
    }
}
