<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MobileDataService;
use Illuminate\Http\JsonResponse;

class BootstrapController extends Controller
{
    public function __construct(
        private readonly MobileDataService $mobileDataService,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'languages' => $this->mobileDataService->languages(),
                'tariffs' => $this->mobileDataService->tariffs(),
                'support_subjects' => $this->mobileDataService->supportSubjects(),
                'driver_statuses' => $this->mobileDataService->driverStatuses(),
                'ride_statuses' => $this->mobileDataService->rideStatuses(),
                'demo_accounts' => [
                    [
                        'role' => 'passenger',
                        'phone' => '7771234567',
                        'password' => 'password',
                    ],
                    [
                        'role' => 'driver',
                        'phone' => '7477471998',
                        'password' => 'qwerty123',
                    ],
                ],
            ],
        ]);
    }
}
