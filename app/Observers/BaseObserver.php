<?php

namespace App\Observers;

use App\Models\User\User;

class BaseObserver
{
    public function currentUser()
    {
        return User::currentUser();
    }

    public function currentUserId()
    {
        return User::currentUserId();
    }
}
