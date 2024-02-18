<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageWasPosted;
use App\Models\Message; // Add this import statement
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *    path="/api/events/{event}/messages",
     *   summary="Get all messages from an event",
     *  tags={"Chat"},
     * @OA\Parameter(
     *  description="ID of event to return",
     * in="query",
     * name="event",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(response=200, description="List of messages"),
     * @OA\Response(response=401, description="Unauthorized"),
     * @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function fetchMessages($eventId)
    {
        $user = auth()->user();

        if (!$user->events_participants->contains($eventId)) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $messages = Message::with('sender')->where('event_id', $eventId)->get();
        return response()->json($messages);
    }

    /**
     * @OA\Post(
     *  path="/api/events/{event}/messages",
     * summary="Send a message to an event",
     * tags={"Chat"},
     * @OA\Parameter(
     * description="ID of event to return",
     * in="path",
     * name="eventId",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     *  description="Content of the message",
     * in="query",
     * name="content",
     * required=true,
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(response=200, description="Message sent"),
     * @OA\Response(response=401, description="Unauthorized"),
     * @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function sendMessages(Request $request, $event)
    {
        $user = auth()->user();

        if (!$user->events_participants->contains($event)) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $message = Message::create([
            'content' => $request->input('content'),
            'sender_id' => $user->id,
            'event_id' => $event,
        ]);

        broadcast(new MessageWasPosted($message, $user))->toOthers();

        return ['status' => 'Message envoyé !'];
    }
}
