<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->is_admin;
    }

    /**
     * @param User $user
     * @param User $centralUser
     * @return bool
     */
    public function view(User $user, User $centralUser)
    {
        return $user->is_admin;
    }

    /**
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        return $user->is_admin;
    }

    /**
     * @param User $user
     * @param User $centralUser
     * @return bool
     */
    public function update(User $user, User $centralUser)
    {
        return $user->is_admin;
    }

    /**
     * @param User $user
     * @param User $centralUser
     * @return bool
     */
    public function delete(User $user, User $centralUser)
    {
        return $user->is_admin;
    }

    /**
     * @param User $user
     * @param User $centralUser
     * @return bool
     */
    public function restore(User $user, User $centralUser)
    {
        return $user->is_admin;
    }

    /**
     * @param User $user
     * @param User $centralUser
     * @return bool
     */
    public function forceDelete(User $user, User $centralUser)
    {
        return $user->is_admin;
    }
}
