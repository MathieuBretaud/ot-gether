<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     @OA\Parameter(
     *         name="lastName",
     *         in="query",
     *         description="User's lastname",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *    @OA\Parameter(
     *        name="firstName",
     *        in="query",
     *        description="User's firstname",
     *        required=true,
     *    @OA\Schema(type="string")
     *    ),
     *    @OA\Parameter(
     *        name="pseudo",
     *        in="query",
     *        description="User's pseudo",
     *        required=true,
     *   @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *        name="city",
     *        in="query",
     *        description="User's city",
     *        required=true,
     *   @OA\Schema(type="string")
     * ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *         name="confirmPassword",
     *         in="query",
     *         description="User's confirm password",
     *         required=true,
     *         @OA\Schema(type="string")
     * ),
     * 
     *     @OA\Response(response="201", description="User registered successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        event(new Registered($user));

        return response()->json('Utilisateur créé');
    }


    /**
     * @OA\Post(
     *     path="/api/check-email",
     *     summary="Verify if an email is already used",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="email")
     *     ),
     *     @OA\Response(response="201", description="isUsed"),
     *     @OA\Response(response="422", description="Errors")
     * )
     */
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $emailExists = User::where('email', $email)->exists();
        return response()->json(['isUsed' => $emailExists]);
    }
}
