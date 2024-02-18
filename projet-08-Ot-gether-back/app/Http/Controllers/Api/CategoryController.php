<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Category"},
     *     summary="Get list of categories",
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        $categories = EventCategory::where('id', '!=', 1)->get()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'image_url' => $category->image_url,
            ];
        });

        return response()->json($categories);
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Category"},
     *     summary="Register a new category",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Category's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="Category created successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function store(Request $request)
    {
        $this->authorize('create');
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $category = EventCategory::create($data);

        return response()->json($category, 201);
    }

    /**
     * @OA\Get(
     *    path="/api/categories/{category}",
     *    summary="Get a category by id",
     *    tags={"Category"},
     * @OA\Parameter(
     *   description="ID of category to return",
     *   in="path",
     *   name="category",
     *   required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(response=200, description="successful operation")
     * )
     */
    public function show(EventCategory $eventCategory)
    {
        return response()->json([
            'id' => $eventCategory->id,
            'name' => $eventCategory->name,
            'image_url' => $eventCategory->image_url,
        ]);
    }

    /**
     * @OA\Patch(
     *  path="/api/categories/{category}",
     *  summary="Update a category by id",
     *  tags={"Category"},
     * @OA\Parameter(
     *   description="ID of category to update",
     *   in="path",
     *   name="category",
     *   required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     *     name="name",
     *   in="query",
     *  description="Category name",
     *  required=false,
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(response=200,description="successful operation")
     * )
     */
    public function update(Request $request, EventCategory $eventCategory)
    {
        $this->authorize('update', $eventCategory);
        $data = $request->validate([
            'name' => 'string|max:255',
        ]);

        $eventCategory->update($data);

        return response()->json($eventCategory);
    }

    /**
     * @OA\Delete(
     *    path="/api/categories/{category}",
     *    summary="Delete a category by id",
     *    tags={"Category"},
     * @OA\Parameter(
     *   description="ID of category to delete",
     *   in="path",
     *   name="category",
     *   required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(response=200,description="successful operation")
     * )
     */
    public function destroy(EventCategory $eventCategory)
    {
        $this->authorize('delete', $eventCategory);
        $eventCategory->events()->update(['event_category_id' => 1]);
        $eventCategory->delete();
    }
}
