<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SupportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function __construct(
        private readonly SupportService $supportService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->supportService->index($request->user()),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'in:complaint,technical,payment,lost-item,other'],
            'message' => ['required', 'string', 'max:5000'],
            'contact_phone' => ['nullable', 'string', 'max:32'],
            'contact_email' => ['nullable', 'email', 'max:255'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->supportService->create($request->user(), $validated),
        ], 201);
    }
}
