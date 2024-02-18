<?php

use App\Broadcasting\ConversationChannel;
use App\Models\Event;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

// Broadcast::channel('events.{id}.messages', ConversationChannel::class);

// // Vérifie si un utilisateur est bien un participant de l'event
// Broadcast::channel('events.{id}.messages', function ($user, $id) {
//     $event = Event::findOrFail($id);
//     return $event->participants->contains($user->id);
// });

Broadcast::channel('events.{id}.messages', function ($user, $id) {

    // $event = Event::findOrFail($id);
    // return $event->participants->contains($user->id);

    return !is_null($user);
});
