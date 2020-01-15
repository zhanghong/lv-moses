<?php

namespace App\Policies\Store;

use App\Models\User\User;
use App\Models\Store\Agent;
use Illuminate\Auth\Access\HandlesAuthorization;

class AgentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Agent $agent)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Agent $agent)
    {
        return true;
    }

    public function delete(User $user, Agent $agent)
    {
        return true;
    }

    public function restore(User $user, Agent $agent)
    {
        return false;
    }

    public function forceDelete(User $user, Agent $agent)
    {
        return false;
    }
}
