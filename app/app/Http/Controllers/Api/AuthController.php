<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\LogoutUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(
        RegisterRequest $request,
        RegisterUserAction $registerUserAction
    ): JsonResponse {
        $result = $registerUserAction->execute($request->validated());

        return response()->json($result, 201);
    }

    /**
     * Authenticate user and generate token.
     */
    public function login(
        LoginRequest $request,
        LoginUserAction $loginUserAction
    ): JsonResponse {
        $result = $loginUserAction->execute($request->validated());

        return response()->json($result);
    }

    /**
     * Logout the authenticated user.
     */
    public function logout(
        Request $request,
        LogoutUserAction $logoutUserAction
    ): JsonResponse {
        $logoutUserAction->execute($request->user());

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Get the authenticated user.
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
