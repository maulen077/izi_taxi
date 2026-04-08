<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DriverApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverApplicationController extends Controller
{
    public function __construct(
        private readonly DriverApplicationService $driverApplicationService,
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'car_brand' => ['required', 'string', 'max:255'],
            'car_model' => ['required', 'string', 'max:255'],
            'car_year' => ['required', 'integer', 'min:1980', 'max:'.date('Y')],
            'car_number' => ['required', 'string', 'max:64'],
            'car_color' => ['required', 'string', 'max:255'],
            'car_tariff' => ['nullable', 'in:economy,comfort,business,minivan'],
            'notes' => ['nullable', 'string'],
            'license_path' => ['nullable'],
            'id_document_path' => ['nullable'],
            'vehicle_registration_path' => ['nullable'],
            'car_photo_front' => ['nullable'],
            'car_photo_side' => ['nullable'],
            'car_photo_interior' => ['nullable'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->driverApplicationService->submit($request->user(), $validated),
        ], 201);
    }
}
