<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    public function getCanUpdateAttribute()
    {
        return false;
    }

    public function getCanDeleteAttribute()
    {
        return false;
    }
}
