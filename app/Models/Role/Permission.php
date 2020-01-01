<?php

namespace App\Models\Role;

use App\Laravue\Acl;
use Illuminate\Database\Query\Builder;

class Permission extends \Spatie\Permission\Models\Permission
{
    public function scopeAllowed($query)
    {
        return $query->where('name', '!=', Acl::PERMISSION_PERMISSION_MANAGE);
    }
}
