<?php

namespace App\Broadcasting;

use App\Models\Event;
use App\Models\User;

class ConversationChannel
{
  public function join(User $user, Event $event)
  {
    return $event->users->contain($user);
  }
}
