<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/users",
     *     tags={"Admin"},
     *     summary="Get list of users",
     * @OA\Parameter(
     *         name="perpage",
     *         in="query",
     *         description="Number of items per page (default is 15)",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $users = User::paginate($request->perpage);
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
