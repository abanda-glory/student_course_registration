<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User registered successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */

    // Registration
    public function register(RegistrationRequest $request): JsonResponse
    {
        // Create the user in the database
        $user = User::create($request->validated());

        // Logs user automatically and generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user)
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Logs in a user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="User logged in sucessfully"),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */

    // Login
    public function login(LoginRequest $request): JsonResponse
    {

        // verify user by email
        $user =  User::where('email', $request->email)->first();

        // User not found
        if (!$user) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Wrong password
        $password = Hash::check($request->password, $user->password);

        if (!$password) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Delete old tokens - a user logging in again should get a fresh token
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token'  => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user)
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Logs a user out",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="User Logged out successfully"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */

    // Logout
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout Successful'
        ], 200);
    }

    /**
     * @OA\Get(
     *      path="/profile",
     *      summary="Displays user's profile",
     *      tags={"Authentication"},
     *      security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="User profile retrieved successfully"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    // Returns user's profile
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'message' => 'Profile successfully retrieved',
            'data' => new UserResource($user)
        ], 200);
    }
}
