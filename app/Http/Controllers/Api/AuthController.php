<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $credentials = $validated;
        try {

            if ($token = JWTAuth::attempt($credentials)) {
                return response()->json([
                'message' => "welocme ".auth()->user()->name,
                'email' => $request->email,
                    'token' => $token
                ], 200);
            }

            return response()->json(['message' => 'Invalid credentials'], 401);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not create token', 'error' => $e->getMessage()], 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        try {

            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }

            $user = User::create($validated);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'User registered successfully.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'token' => $token
                ]
            ], 201);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'An error occurred during registration.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Successfully logged out']);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Failed to logout, please try again.', 'error' => $e->getMessage()], 500);
        }
    }

    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json(['user' => $user], 200);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not retrieve user', 'error' => $e->getMessage()], 500);
        }
    }
}
