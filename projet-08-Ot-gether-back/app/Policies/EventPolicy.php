<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): Response
    {
        return $user->id === $event->creator_id
            ? Response::allow()
            : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): Response
    {
        return $user->is_admin === true
            ? Response::allow()
            : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): Response
    {
        return $user->is_admin === true
            ? Response::allow()
            : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): Response
    {
        return $user->is_admin === true
            ? Response::allow()
            : Response::deny('You must be an administrator.');
    }
}
