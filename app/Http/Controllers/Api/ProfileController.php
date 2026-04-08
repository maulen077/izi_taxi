<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileService $profileService,
    ) {
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->profileService->me($request->user()),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'language' => ['nullable', 'in:ru,kk'],
            'avatar_url' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->profileService->update($request->user(), $validated),
        ]);
    }
}
