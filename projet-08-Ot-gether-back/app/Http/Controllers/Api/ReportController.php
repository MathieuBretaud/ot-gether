<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Event;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/events/{event}/report",
     *   summary="Report an event",
     *   tags={"Report"},
     * @OA\Parameter(
     *  description="ID of event to report",
     *  in="path",
     *  name="event",
     *  required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     *   description="Message to send with the report",
     *   in="query",
     *   name="message",
     *   required=true,
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(response=200, description="Event reported"),
     * @OA\Response(response=401, description="Unauthorized"),
     * @OA\Response(response=404, description="Event not found")
     * )
     */
    public function reportEvent(Request $request, Event $event)
    {
        try {
            $user = auth()->user();

            if ($event->reports()->where('user_id', $user->id)->exists()) {
                return response()->json(['message' => 'Vous avez déjà signalé cet événement'], 409);
            }
            $reports = new Report;
            $reports->message = $request->message;
            $reports->event_id = $event->id;
            $reports->user_id = $user->id;
            $reports->save();

            return response()->json([
                'report' => $reports,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur est survenue', 'error' => $e->getCode()], 500);
        }
    }
}
