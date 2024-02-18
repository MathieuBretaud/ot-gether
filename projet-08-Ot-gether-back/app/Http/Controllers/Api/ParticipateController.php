<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class ParticipateController extends Controller
{
    /**
     * Add User Auth paticipate at an Event.
     * @OA\Post(
     *   path="/api/events/{event}/participate",
     *   summary="Add User Auth paticipate at an Event",
     *   tags={"Participate"},
     * @OA\Parameter(
     *  description="ID of event to participate",
     *  in="path",
     *  name="event",
     *  required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(response=200, description="User participate at an event"),
     * @OA\Response(response=401, description="Unauthorized"),
     * @OA\Response(response=404, description="Event not found")
     * )
     */
    public function participate(Request $request, Event $event)
    {
        try {
            $user = auth()->user();

            if ($event->participants()->where('user_id', $user->id)->exists()) {
                return response()->json(['message' => 'Vous participez déjà à cet événement'], 409);
            }

            if ($event->participants()->count() >= $event->participant_max) {
                return response()->json(['message' => 'Cet événement est complet'], 409);
            }

            $event->participants()->attach($user);

            return response()->json(['message' => 'Vous êtes maintenant inscrit à cet événement'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur est survenue', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove User Auth paticipate at an Event.
     * @OA\Delete(
     *  path="/api/events/{event}/participate",
     * summary="Remove User Auth paticipate at an Event",
     *  tags={"Participate"},
     * @OA\Parameter(
     * description="ID of event to participate",
     * in="path",
     * name="event",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(response=200, description="User participate at an event"),
     * @OA\Response(response=401, description="Unauthorized"),
     * @OA\Response(response=404, description="Event not found")
     * )
     * 
     */
    public function unsubscribe(Request $request, Event $event)
    {
        try {
            $user = auth()->user();

            if (!$event->participants()->where('user_id', $user->id)->exists()) {
                return response()->json(['message' => 'Vous ne participez pas à cet événement'], 409);
            }

            $event->participants()->detach($user);

            return response()->json(['message' => 'Vous n\'êtes plus inscrit à cet événement'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur est survenue', 'error' => $e->getMessage()], 500);
        }
    }
}
