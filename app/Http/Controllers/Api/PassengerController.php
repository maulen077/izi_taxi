<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Services\PassengerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PassengerController extends Controller
{
    public function __construct(
        private readonly PassengerService $passengerService,
    ) {
    }

    public function home(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->passengerService->home($request->user()),
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->passengerService->history($request->user()),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_mode' => ['nullable', 'in:taxi,delivery'],
            'tariff' => ['nullable', 'in:economy,comfort,business,minivan'],
            'pickup' => ['nullable', 'string', 'max:255'],
            'pickup_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'pickup_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'destination' => ['nullable', 'string', 'max:255'],
            'destination_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'destination_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'skip_destination' => ['nullable', 'boolean'],
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'has_luggage' => ['nullable', 'boolean'],
            'luggage_size' => ['nullable', 'in:regular,large'],
            'sender_phone' => ['nullable', 'string', 'max:32'],
            'receiver_phone' => ['nullable', 'string', 'max:32'],
            'notes' => ['nullable', 'string'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->passengerService->createRide($request->user(), $validated),
        ], 201);
    }

    public function show(Request $request, Ride $ride): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->passengerService->showRide($request->user(), $ride),
        ]);
    }

    public function tracking(Request $request, Ride $ride): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->passengerService->trackRide($request->user(), $ride),
        ]);
    }

    public function rate(Request $request, Ride $ride): JsonResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->passengerService->rateRide($request->user(), $ride, $validated),
        ]);
    }
}
