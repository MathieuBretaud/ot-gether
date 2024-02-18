<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /** 😄
     * Determine whether the user can view any models. 
     */
    // public function viewAny(User $user): Response
    // {
    //     // return $user->is_admin === true
    //     //     ? Response::allow()
    //     //     : Response::deny('You must be an administrator.');
    // }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): Response
    {
        return $user->id === $model->id || $user->is_admin === true
            ? Response::allow()
            : Response::deny('You must be an administrator or on the good profil.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): Response
    {
        return $user->is_admin === true
            ? Response::allow()
            : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): Response
    {
        return $user->is_admin === true
            ? Response::allow()
            : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): Response
    {
        return $user->is_admin === true
            ? Response::allow()
            : Response::deny('You must be an administrator.');
    }
}
