<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;

class Property extends Model
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
        return $this->hasMany(Selector::class);
    }
}
