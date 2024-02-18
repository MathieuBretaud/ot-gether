<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventUpdateRequest;
use App\Models\Event;
use App\Models\EventTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class AdminEventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/events",
     *     tags={"Admin"},
     *     summary="Get list of admin events",
     * @OA\Parameter(
     *         name="perpage",
     *         in="query",
     *         description="Number of items per page (default is 15)",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(response=200, description="List of admin events"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $events = Event::withTrashed()->with('participants')->with('reports')->orderBy('created_at', 'desc')->paginate($request->perpage);
        return response()->json($events);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/events",
     *     tags={"Admin"},
     *     summary="Register a new event admin",
     *  @OA\Parameter(
     *      name="isIRl", 
     *      in="query",
     *      description="Is event in Real Life",
     *      required=true,
     *      @OA\Schema(type="boolean")
     *  ),
     *  @OA\Parameter(
     *      name="title", 
     *      in="query",
     *      description="Event title",
     *      required=true,
     *      @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(
     *      name="categorie", 
     *      in="query",
     *      description="Event category",
     *      required=true,
     *      @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(
     *      name="participant_min", 
     *      in="query",
     *      description="Event minimal participant",
     *      required=true,
     *      @OA\Schema(type="integer")
     *  ),
     *  @OA\Parameter(
     *      name="participant_max",
     *      in="query",
     *      description="Event maximum participant",
     *      required=true,
     *      @OA\Schema(type="integer")
     *  ),
     *  @OA\Parameter(
     *      name="start_date",
     *      in="query",
     *      description="Event start date",
     *      required=true,
     *      @OA\Schema(type="date")
     *  ),
     *  @OA\Parameter(
     *      name="end_date",
     *      in="query",
     *      description="Event's inscription end date",
     *      required=true,
     *      @OA\Schema(type="date")
     *  ),
     *  @OA\Parameter(
     *      name="city",
     *      in="query",
     *      description="Event city",
     *      required=false,
     *      @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(
     *      name="region",
     *      in="query",
     *      description="Event region",
     *      required=false,
     *      @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(
     *      name="address",
     *      in="query",
     *      description="Event address",
     *      required=false,
     *      @OA\Schema(type="string")
     *  ),
     *  @OA\Response(response="201", description="Event registered successfully"),
     *  @OA\Response(response="422", description="Add event error"),
     *  @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function store(Request $request)
    {
        $title = $request->validated()['title'];
        $slug = Str::slug($title);
        $event = Event::create($request->validated());
        $event->slug = $slug;
        $event->picture = "https://picsum.photos/300";
        $event->category()->associate($request->validated()['event_category_id']);
        $event->creator()->associate(auth()->user());
        $event->tag()->associate(EventTag::find(1));
        //        $event->tag()->associate($request->validated()['event_tag_id']);
        // $user = auth()->user();

        $event->save();

        return response()->json($event, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/events/{event}",
     *     tags={"Admin"},
     *     summary="Get one admin event",
     *     @OA\Parameter(
     *         name="event",
     *         in="path",
     *         description="ID of the admin event",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Admin event details"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Admin event not found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show(Event $event)
    {
        return response()->json($event->load('participants'));
    }

    /**
     * @OA\Patch(
     *     path="/api/admin/events/{event}",
     *     tags={"Admin"},
     *     summary="Update one admin event",
     *     @OA\Parameter(
     *         name="event",
     *         in="path",
     *         description="ID of the admin event",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Admin event updated successfully"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Admin event not found")
     * )
     */
    public function update(EventUpdateRequest $request, Event $event, string $id)
    {
        $this->authorize('update', $event);
        $data = $request->validated();
        $event->update($data);

        return response()->json($event);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/events/{event}",
     *     tags={"Admin"},
     *     summary="Delete one admin event",
     *     @OA\Parameter(
     *         name="event",
     *         in="path",
     *         description="ID of the admin event",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Admin event deleted successfully"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Admin event not found")
     * )
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->messages()->update(['event_id' => null]);
        $event->delete();
    }
}
