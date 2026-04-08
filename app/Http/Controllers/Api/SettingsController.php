<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(
        private readonly SettingsService $settingsService,
    ) {
    }

    public function languages(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->settingsService->languages(),
        ]);
    }

    public function language(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'language' => ['required', 'in:ru,kk'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->settingsService->updateLanguage($request->user(), $validated),
        ]);
    }
}
