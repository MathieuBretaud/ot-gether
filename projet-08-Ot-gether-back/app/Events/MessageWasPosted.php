<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable as EventsDispatchable;
use Illuminate\Support\Facades\Log;

class MessageWasPosted implements ShouldBroadcast
{
  use EventsDispatchable, InteractsWithSockets, SerializesModels;

  public $message;
  public $user;

  public function __construct(Message $message, User $user)
  {
    $this->message = $message;
    $this->user = $user;
  }

  public function broadcastOn()
  {
    return new Channel('events.' . $this->message->event_id . '.messages');
  }

  public function broadcastAs()
  {
    return 'message.posted';
  }

  public function broadcastWith()
  {
    return [
      'message' => [
        'id' => $this->message->id,
        'content' => $this->message->content,
        'created_at' => $this->message->created_at,
        'sender' => [
          'pseudo' => $this->user->pseudo,
        ],
      ],
    ];
  }
}
