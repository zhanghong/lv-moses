<?php

namespace App\Policies\Shop;

use App\Models\User\User;
use App\Models\Shop\Brand;
use Illuminate\Auth\Access\HandlesAuthorization;

class BrandPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Brand $brand)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Brand $brand)
    {
        return true;
    }

    public function delete(User $user, Brand $brand)
    {
        return true;
    }

    public function restore(User $user, Brand $brand)
    {
        return false;
    }

    public function forceDelete(User $user, Brand $brand)
    {
        return false;
    }
}
