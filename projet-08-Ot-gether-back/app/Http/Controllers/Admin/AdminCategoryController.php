<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/categories",
     *     tags={"Admin"},
     *     summary="Get list of categories",
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function index()
    {
        $category = EventCategory::all();

        return response()->json($category);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/categories",
     *     tags={"Admin"},
     *     summary="Create one new category",
     *     @OA\Parameter(
     *      name="name", 
     *      in="path",
     *      description="name category",
     *      required=true,
     *      @OA\Schema(type="string")
     *  ),
     *     @OA\Response(response="201", description="Category create success"),
     *     @OA\Response(response="422", description="Add category error"),
     *     security={{"bearerAuth":{}}}
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
     *     path="/api/admin/categories/{category}",
     *     summary="Get an category by id",
     *     tags={"Admin"},
     *     summary="Create one category",
     *     @OA\Parameter(
     *      description="ID of category to return",
     *      in="path",
     *      name="categoryId",
     *      required=true,
     *      @OA\Schema(type="integer")
     *    ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="category not found"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show(EventCategory $eventCategory)
    {
        return $eventCategory;
    }

    /**
     * @OA\Patch(
     *     path="/api/admin/categories/{category}",
     *     tags={"Admin"},
     *     summary="Update one category by id",
     * @OA\Parameter(
     *   description="ID of category to update",
     *   in="query",
     *   name="categoryId",
     *   required=true,
     *   @OA\Schema(type="integer")
     *  ),
     *  @OA\Response(response=200,description="successful operation"),
     *  @OA\Response(response="422", description="Update category error"),
     *  @OA\Response(response="401", description="Unauthorized"),
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
     *     path="/api/admin/categories/{category}",
     *     tags={"Admin"},
     *     summary="Delete one category by id",
     * @OA\Parameter(
     *      description="ID of category to delete",
     *      in="path",
     *      name="categoryId",
     *      required=true,
     *      @OA\Schema(type="integer")
     *    ),
     *    @OA\Response(response=200,description="successful operation"),
     *    @OA\Response(response="404", description="Category not found"),
     *    @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function destroy(EventCategory $eventCategory)
    {
        $this->authorize('delete', $eventCategory);
        $eventCategory->events()->update(['event_category_id' => 1]);
        $eventCategory->delete();
    }
}
