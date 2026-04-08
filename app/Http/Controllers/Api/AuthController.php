<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['nullable', 'in:passenger,driver'],
            'language' => ['nullable', 'in:ru,kk'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->authService->register($validated),
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:32'],
            'password' => ['required', 'string', 'min:1'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->authService->login($validated),
        ]);
    }

    public function reset(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:32'],
            'password' => ['required', 'string', 'min:6'],
            'otp' => ['nullable', 'string'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->authService->resetPassword($validated),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }
}
