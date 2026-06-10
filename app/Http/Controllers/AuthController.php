<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
            'user' => $user
        ], 201);
    }

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
            'user' => $user
        ], 200);
    }

    // Logout
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout Successful'
        ], 200);
    }
}
