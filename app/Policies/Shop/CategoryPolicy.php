<?php

namespace App\Policies\Shop;

use App\Models\User\User;
use App\Models\Shop\Shop;
use App\Models\Shop\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Category $category)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Category $category)
    {
        return true;
    }

    public function delete(User $user, Category $category)
    {
        return true;
    }

    public function restore(User $user, Category $category)
    {
        return false;
    }

    public function forceDelete(User $user, Category $category)
    {
        return false;
    }
}
