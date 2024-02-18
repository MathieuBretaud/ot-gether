<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/events",
     *     tags={"Events"},
     *     summary="Get list of events",
     *     @OA\Parameter(
     *         name="perpage",
     *         in="query",
     *         description="Number of items per page (default is 15)",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function index(Request $request)
    {
        $category = $request->query('category');
        $query = Event::query();

        try {
            if (!empty($category)) {
                $catExists = EventCategory::findOrFail($category);
                $query->where('event_category_id', $category);
                if ($query->doesntExist()) {
                    return response()->json(['message' => 'Aucun événement trouvé pour cette catégorie'], 404);
                }
            }

            $query->orderByRaw('start_date >= CURRENT_DATE DESC')
                ->orderBy('start_date', 'asc');

            $events = $query->latest()->with('participants')->with('reports')->paginate($request->perpage);


            return response()->json($events);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur est survenue', 'error' => $e->getCode()]);
        }
    }

    /**
     * @OA\Post(
     *  path="/api/events",
     *  tags={"Events"},
     *  summary="Register a new event",
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
    public function store(EventRequest $request)
    {
        $validatedData = $request->validated();
        $title = $validatedData['title'];
        $slug = Str::slug($title);
        $categoryId = $validatedData['event_category_id'];

        $event = new Event($validatedData);
        $event->slug = $slug;

        $category = EventCategory::find($categoryId);
        if ($category) {
            $event->picture = $category->image_url;
        } else {
            $event->picture = "https://picsum.photos/300";
        }

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
     *    path="/api/events/{event}",
     *    summary="Get an event by id",
     *    tags={"Events"},
     *    @OA\Parameter(
     *      description="ID of event to return",
     *      in="path",
     *      name="eventId",
     *      required=true,
     *      @OA\Schema(type="integer")
     *    ),
     *    @OA\Response(response=200, description="successful operation"),
     *    @OA\Response(response="404", description="Event not found"),
     *    @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function show(Event $event)
    {
        return response()->json($event->load('participants')->load('reports'));
    }

    /**
     * @OA\Patch(
     *  path="/api/events/{event}",
     *  summary="Update an event by id",
     *  tags={"Events"},
     *  @OA\Parameter(
     *   description="ID of event to update",
     *   in="path",
     *   name="eventId",
     *   required=true,
     *   @OA\Schema(type="integer")
     *  ),
     *  @OA\Parameter(
     *      name="isIRl", 
     *      in="query",
     *      description="Is event in Real Life",
     *      required=false,
     *      @OA\Schema(type="boolean")
     *  ),
     *  @OA\Parameter(
     *      name="title", 
     *      in="query",
     *      description="Event title",
     *      required=false,
     *      @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(
     *      name="categorie", 
     *      in="query",
     *      description="Event category",
     *      required=false,
     *      @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(
     *      name="participant_min", 
     *      in="query",
     *      description="Event minimal participant",
     *      required=false,
     *      @OA\Schema(type="integer")
     *  ),
     *  @OA\Parameter(
     *      name="participant_max", 
     *      in="query",
     *      description="Event maximum participant",
     *      required=false,
     *      @OA\Schema(type="integer")
     *  ),
     *  @OA\Parameter(
     *      name="start_date", 
     *      in="query",
     *      description="Event start date",
     *      required=false,
     *      @OA\Schema(type="date")
     *  ),
     *  @OA\Parameter(
     *      name="end_date", 
     *      in="query",
     *      description="Event's inscription end date",
     *      required=false,
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
     *  @OA\Response(response=200,description="successful operation"),
     *  @OA\Response(response="422", description="Update event error"),
     *  @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function update(EventUpdateRequest $request, Event $event)
    {
        $this->authorize('update', $event);
        $data = $request->validated();

        $newCategoryId = $data['event_category_id'];
        $newCategory = EventCategory::find($newCategoryId);

        if ($newCategory) {
            $event->picture = $newCategory->image_url;
        } else {
            $event->picture = "https://picsum.photos/300";
        }

        $event->update($data);

        return response()->json($event);
    }

    /**
     * @OA\Delete(
     *    path="/api/events/{event}",
     *    summary="Delete an event by id",
     *    tags={"Events"},
     *    @OA\Parameter(
     *      description="ID of event to delete",
     *      in="path",
     *      name="eventId",
     *      required=true,
     *      @OA\Schema(type="integer")
     *    ),
     *  @OA\Response(response=200,description="successful operation"),
     *  @OA\Response(response="404", description="Event not found"),
     *  @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->messages()->update(['event_id' => null]);
        $event->delete();
    }
}
