<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\DriverStatus;
use App\Enums\TariffType;
use App\Enums\UserRole;
use App\Models\DriverApplication;
use App\Models\DriverProfile;
use App\Models\User;

class DriverApplicationService
{
    public function __construct(
        private readonly MobileDataService $mobileDataService,
    ) {
    }

    public function submit(User $user, array $data): array
    {
        $application = DriverApplication::updateOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $this->mobileDataService->normalizePhone($data['phone']),
                'email' => $data['email'] ?? $user->email,
                'car_brand' => $data['car_brand'],
                'car_model' => $data['car_model'],
                'car_year' => $data['car_year'],
                'car_number' => $data['car_number'],
                'car_color' => $data['car_color'],
                'license_path' => $data['license_path'] ?? null,
                'id_document_path' => $data['id_document_path'] ?? null,
                'vehicle_registration_path' => $data['vehicle_registration_path'] ?? null,
                'car_photo_front' => $data['car_photo_front'] ?? null,
                'car_photo_side' => $data['car_photo_side'] ?? null,
                'car_photo_interior' => $data['car_photo_interior'] ?? null,
                'status' => ApplicationStatus::Approved,
                'submitted_at' => now(),
                'reviewed_at' => now(),
                'notes' => $data['notes'] ?? null,
            ]
        );

        $user->forceFill([
            'role' => UserRole::Driver,
            'driver_status' => DriverStatus::Offline,
        ])->save();

        DriverProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $this->mobileDataService->normalizePhone($data['phone']),
                'email' => $data['email'] ?? $user->email,
                'car_brand' => $data['car_brand'],
                'car_model' => $data['car_model'],
                'car_year' => $data['car_year'],
                'car_number' => $data['car_number'],
                'car_color' => $data['car_color'],
                'car_tariff' => $data['car_tariff'] ?? TariffType::Economy,
                'car_photo_front' => $data['car_photo_front'] ?? null,
                'car_photo_side' => $data['car_photo_side'] ?? null,
                'car_photo_interior' => $data['car_photo_interior'] ?? null,
                'license_path' => $data['license_path'] ?? null,
                'id_document_path' => $data['id_document_path'] ?? null,
                'vehicle_registration_path' => $data['vehicle_registration_path'] ?? null,
                'application_status' => ApplicationStatus::Approved,
                'submitted_at' => now(),
                'approved_at' => now(),
                'notes' => $data['notes'] ?? null,
            ]
        );

        return [
            'application' => $this->mobileDataService->serializeDriverApplication($application->fresh()),
            'user' => $this->mobileDataService->serializeUser($user->fresh(['driverProfile'])),
            'message' => 'Application approved successfully.',
        ];
    }
}
