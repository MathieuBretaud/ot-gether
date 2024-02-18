<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"User"},
     *     summary="Get a list of users",
     *     @OA\Parameter(
     *         name="perpage",
     *         in="query",
     *         description="Number of items per page (default is 15)",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(response=200, description="List of users"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny');
        $users = User::paginate($request->perpage);

        return response()->json($users);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{user}",
     *     tags={"User"},
     *     summary="Get a user by ID",
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User details"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * @OA\Put(
     *     path="/api/users/{user}",
     *     tags={"User"},
     *     summary="Update a user by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="lastname",
     *         in="query",
     *         description="User lastname",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="firstname",
     *         in="query",
     *         description="User firstname",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         description="User city",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="pseudo",
     *         in="query",
     *         description="User pseudo",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User email",
     *         required=false,
     *         @OA\Schema(type="email")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User password",
     *         required=false,
     *         @OA\Schema(type="password")
     *     ),
     *     
     *     @OA\Response(response=200, description="User updated successfully"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=422, description="Validation errors")
     * )
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $data = $request->validated();
        $user->update($data);

        return response($data);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{user}",
     *     tags={"User"},
     *     summary="Delete a user by ID",
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="User deleted successfully"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
    }

    /**
     * @OA\Get(
     *     path="/api/users/my-events",
     *     tags={"User"},
     *     summary="Get the events associated with the authenticated user",
     *     @OA\Response(response=200, description="List of events associated with the user"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="An error occurred")
     * )
     */
    public function myEvents()
    {
        try {
            $user = auth()->user();
            $events = $user->events_participants()->with('category', 'tag', 'participants')->get();

            return response()->json([$events, 'message' => 'Liste des événements de l utilisateur']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur est survenue', 'error' => $e->getMessage()], 500);
        }
    }
}
