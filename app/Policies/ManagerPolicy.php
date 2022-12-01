<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManagerPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isManager()) {
            return true;
        }
    }

    public function create(User $user)
    {
        return true;
    }

    public function delete(User $user)
    {
        return true;
    }

    public function show(User $user)
    {
        return true;
    }

    public function getRecords(User $user)
    {
        return true;
    }
}
