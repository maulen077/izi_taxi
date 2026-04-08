<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Services\DriverService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function __construct(
        private readonly DriverService $driverService,
    ) {
    }

    public function home(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->driverService->home($request->user()),
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->driverService->history($request->user()),
        ]);
    }

    public function status(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:offline,online'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->driverService->updateStatus($request->user(), $validated),
        ]);
    }

    public function profile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
            'balance' => ['nullable', 'integer', 'min:0'],
            'trust_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'car_brand' => ['nullable', 'string', 'max:255'],
            'car_model' => ['nullable', 'string', 'max:255'],
            'car_year' => ['nullable', 'integer', 'min:1980', 'max:'.date('Y')],
            'car_number' => ['nullable', 'string', 'max:64'],
            'car_color' => ['nullable', 'string', 'max:255'],
            'car_photo_front' => ['nullable'],
            'car_photo_side' => ['nullable'],
            'car_photo_interior' => ['nullable'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->driverService->updateProfile($request->user(), $validated),
        ]);
    }

    public function accept(Request $request, Ride $ride): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->driverService->acceptRide($request->user(), $ride),
        ]);
    }

    public function reject(Request $request, Ride $ride): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->driverService->rejectRide($request->user(), $ride),
        ]);
    }

    public function arrived(Request $request, Ride $ride): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->driverService->arrived($request->user(), $ride),
        ]);
    }

    public function start(Request $request, Ride $ride): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->driverService->startRide($request->user(), $ride),
        ]);
    }

    public function complete(Request $request, Ride $ride): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->driverService->completeRide($request->user(), $ride),
        ]);
    }
}
