<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventTagRequest;
use App\Models\EventTag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tags",
     *     tags={"Tag"},
     *     summary="Get list of tags",
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index(Request $request)
    {
        $eventTags = EventTag::paginate($request->perpage);

        return response()->json($eventTags);
    }

    /**
     * @OA\Post(
     *     path="/api/tags",
     *     tags={"Tag"},
     *     summary="Register a new tag",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Tag's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="Tag created successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function store(EventTagRequest $request)
    {
        $this->authorize('create');
        $eventTag = EventTag::create($request->validated());

        return response()->json($eventTag, 201);
    }

    /**
     * @OA\Get(
     *    path="/api/tags/{tag}",
     *    summary="Get a tag by id",
     *    tags={"Tag"},
     * @OA\Parameter(
     *   description="ID of tag to return",
     *   in="path",
     *   name="tag",
     *   required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(response=200, description="successful operation")
     * )
     */
    public function show(EventTag $eventTag)
    {
        return $eventTag;
    }

    /**
     * @OA\Patch(
     *  path="/api/tags/{tag}",
     *  summary="Update a tag by id",
     *  tags={"Tag"},
     * @OA\Parameter(
     *   description="ID of tag to update",
     *   in="path",
     *   name="tag",
     *   required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     *     name="name",
     *    in="query",
     *   description="Tag name",
     *  required=false,
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(response=200,description="successful operation")
     * )
     */
    public function update(EventTagRequest $request, EventTag $eventTag)
    {
        $this->authorize('update', $eventTag);
        $data = $request->validate([
            'name' => 'string|max:255',
        ]);
        $eventTag->update($data);

        return response()->json($eventTag);
    }

    /**
     * @OA\Delete(
     *    path="/api/tags/{tag}",
     *    summary="Delete a tag by id",
     *    tags={"Tag"},
     * @OA\Parameter(
     *   description="ID of tag to delete",
     *   in="path",
     *   name="tag",
     *   required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(response=200,description="successful operation")
     * )
     */
    public function destroy(EventTag $eventTag)
    {
        $this->authorize('delete', $eventTag);
        $eventTag->delete();
    }
}
