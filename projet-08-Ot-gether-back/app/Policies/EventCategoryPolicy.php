<?php

namespace App\Policies;

use App\Models\EventCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventCategoryPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->is_admin === true
            ? Response::allow()
            : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EventCategory $eventCategory): Response
    {
        return $user->is_admin === true
            ? Response::allow()
            : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EventCategory $eventCategory): Response
    {
        return $user->is_admin === true
            ? Response::allow()
            : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EventCategory $eventCategory): Response
    {
        return $user->is_admin === true
            ? Response::allow()
            : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EventCategory $eventCategory): Response
    {
        return $user->is_admin === true
            ? Response::allow()
            : Response::deny('You must be an administrator.');
    }
}
