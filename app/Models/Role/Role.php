<?php
namespace App\Models\Role;

use App\Laravue\Acl;
use Spatie\Permission\Models\Permission;

class Role extends \Spatie\Permission\Models\Role
{
    public function isAdmin(): bool
    {
        return $this->name === Acl::ROLE_ADMIN;
    }
}
