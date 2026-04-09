<?php

namespace App\Services;

use App\Enums\DriverStatus;
use App\Enums\OrderMode;
use App\Enums\RideStatus;
use App\Enums\TariffType;
use App\Enums\UserRole;
use App\Models\DriverLocation;
use App\Models\DriverProfile;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class DriverService
{
    public function __construct(
        private readonly MobileDataService $mobileDataService,
    ) {
    }

    public function home(User $user): array
    {
        if (! $user->role instanceof UserRole || $user->role !== UserRole::Driver) {
            throw ValidationException::withMessages([
                'role' => 'Driver role required.',
            ]);
        }

        $activeRide = Ride::query()
            ->with(['passenger', 'driver.driverProfile'])
            ->whereIn('status', [
                RideStatus::Accepted->value,
                RideStatus::Arriving->value,
                RideStatus::PickedUp->value,
                RideStatus::InProgress->value,
            ])
            ->where('driver_id', $user->id)
            ->latest()
            ->first();

        $incomingOrder = Ride::query()
            ->with(['passenger', 'driver.driverProfile'])
            ->where('status', RideStatus::Searching->value)
            ->whereNull('driver_id')
            ->where(function ($query) use ($user) {
                $this->applyRideEligibilityFilter($query, $user);
            })
            ->latest()
            ->first();

        $todayEarnings = Ride::query()
            ->where('driver_id', $user->id)
            ->where('status', RideStatus::Completed->value)
            ->whereDate('completed_at', today())
            ->sum('price');

        return [
            'user' => $this->mobileDataService->serializeUser($user->fresh(['driverProfile'])),
            'driver_status' => $user->driver_status instanceof DriverStatus ? $user->driver_status->value : (string) $user->driver_status,
            'driver_status_label' => $user->driver_status instanceof DriverStatus ? $user->driver_status->label() : (string) $user->driver_status,
            'incoming_order' => $incomingOrder ? $this->mobileDataService->serializeRide($incomingOrder) : null,
            'active_ride' => $activeRide ? $this->mobileDataService->serializeRide($activeRide) : null,
            'metrics' => [
                'balance' => $user->balance,
                'today_earnings' => (int) $todayEarnings,
                'trust_score' => $user->trust_score,
                'distance_to_pickup' => $activeRide ? (float) $activeRide->distance_km : 1.2,
                'time_to_pickup' => $activeRide ? (int) $activeRide->duration_minutes : 3,
            ],
        ];
    }

    public function history(User $user): array
    {
        $rides = Ride::query()
            ->with(['passenger'])
            ->where('driver_id', $user->id)
            ->whereIn('status', [RideStatus::Completed->value, RideStatus::Cancelled->value])
            ->latest('completed_at')
            ->get();

        return [
            'rides' => $rides->map(fn (Ride $ride) => $this->mobileDataService->serializeRide($ride))->values(),
            'total_earnings' => (int) $rides->sum('price'),
        ];
    }

    public function updateStatus(User $user, array $data): array
    {
        $status = ($data['status'] ?? DriverStatus::Offline->value) === DriverStatus::Online->value
            ? DriverStatus::Online
            : DriverStatus::Offline;

        $user->forceFill([
            'driver_status' => $status,
        ])->save();

        return [
            'user' => $this->mobileDataService->serializeUser($user->fresh(['driverProfile'])),
        ];
    }

    public function updateProfile(User $user, array $data): array
    {
        $user->fill(array_filter([
            'name' => $data['name'] ?? null,
            'phone' => isset($data['phone']) ? $this->mobileDataService->normalizePhone($data['phone']) : null,
            'email' => $data['email'] ?? null,
            'password' => $data['password'] ?? null,
            'balance' => $data['balance'] ?? null,
            'trust_score' => $data['trust_score'] ?? null,
        ], fn ($value) => $value !== null && $value !== ''));
        $user->save();

        $profile = DriverProfile::firstOrCreate(['user_id' => $user->id]);
        $profile->fill(array_filter([
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'phone' => isset($data['phone']) ? $this->mobileDataService->normalizePhone($data['phone']) : null,
            'email' => $data['email'] ?? null,
            'car_brand' => $data['car_brand'] ?? null,
            'car_model' => $data['car_model'] ?? null,
            'car_year' => $data['car_year'] ?? null,
            'car_number' => $data['car_number'] ?? null,
            'car_color' => $data['car_color'] ?? null,
            'car_photo_front' => $data['car_photo_front'] ?? null,
            'car_photo_side' => $data['car_photo_side'] ?? null,
            'car_photo_interior' => $data['car_photo_interior'] ?? null,
        ], fn ($value) => $value !== null && $value !== ''));
        $profile->save();

        return [
            'user' => $this->mobileDataService->serializeUser($user->fresh(['driverProfile'])),
        ];
    }

    public function updateLocation(User $user, array $data): array
    {
        if (! $user->role instanceof UserRole || $user->role !== UserRole::Driver) {
            throw ValidationException::withMessages([
                'role' => 'Driver role required.',
            ]);
        }

        $location = DriverLocation::updateOrCreate(
            ['user_id' => $user->id],
            [
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'heading' => $data['heading'] ?? null,
                'accuracy' => $data['accuracy'] ?? null,
            ]
        );

        return [
            'location' => $this->mobileDataService->serializeDriverLocation($user->fresh('currentLocation')),
            'updated_at' => optional($location->updated_at)?->toIso8601String(),
        ];
    }

    public function acceptRide(User $user, Ride $ride): array
    {
        $this->guardRideBelongsToDriverQueue($user, $ride);

        $ride->forceFill([
            'driver_id' => $user->id,
            'status' => RideStatus::Accepted,
            'accepted_at' => now(),
        ])->save();

        $user->forceFill(['driver_status' => DriverStatus::EnRouteToPickup])->save();

        return $this->rideResponse($ride);
    }

    public function rejectRide(User $user, Ride $ride): array
    {
        $this->guardRideBelongsToDriverQueue($user, $ride);

        $this->applyReliabilityPenalty($user);

        $ride->forceFill([
            'status' => RideStatus::Searching,
            'driver_id' => null,
        ])->save();

        return $this->rideResponse($ride);
    }

    public function arrived(User $user, Ride $ride): array
    {
        $this->guardAssignedRide($user, $ride);

        $ride->forceFill([
            'status' => RideStatus::PickedUp,
            'arrived_at' => now(),
        ])->save();

        $user->forceFill(['driver_status' => DriverStatus::PassengerWaiting])->save();

        return $this->rideResponse($ride);
    }

    public function startRide(User $user, Ride $ride): array
    {
        $this->guardAssignedRide($user, $ride);

        $ride->forceFill([
            'status' => RideStatus::InProgress,
            'started_at' => now(),
        ])->save();

        $user->forceFill(['driver_status' => DriverStatus::InProgress])->save();

        return $this->rideResponse($ride);
    }

    public function completeRide(User $user, Ride $ride): array
    {
        $this->guardAssignedRide($user, $ride);

        $ride->forceFill([
            'status' => RideStatus::Completed,
            'completed_at' => now(),
        ])->save();

        $user->forceFill([
            'driver_status' => DriverStatus::Online,
            'balance' => $user->balance + $ride->price,
        ])->save();

        return $this->rideResponse($ride);
    }

    public function trackRide(User $user, Ride $ride): array
    {
        if (
            (int) $ride->driver_id !== (int) $user->id
            && $ride->status !== RideStatus::Searching
        ) {
            throw ValidationException::withMessages([
                'ride' => 'Ride not assigned to this driver.',
            ]);
        }

        return [
            'ride' => $this->mobileDataService->serializeRide($ride->loadMissing(['passenger', 'driver.driverProfile', 'driver.currentLocation'])),
            'tracking' => $this->mobileDataService->serializeRideTracking($ride),
        ];
    }

    private function guardRideBelongsToDriverQueue(User $user, Ride $ride): void
    {
        if ($ride->status !== RideStatus::Searching || $ride->driver_id !== null) {
            throw ValidationException::withMessages([
                'ride' => 'Ride is not available for acceptance.',
            ]);
        }

        if (! $this->driverCanAcceptRide($user, $ride)) {
            throw ValidationException::withMessages([
                'ride' => 'Ride is not available for this driver.',
            ]);
        }
    }

    private function guardAssignedRide(User $user, Ride $ride): void
    {
        if ((int) $ride->driver_id !== (int) $user->id) {
            throw ValidationException::withMessages([
                'ride' => 'Ride not assigned to this driver.',
            ]);
        }
    }

    private function rideResponse(Ride $ride): array
    {
        return [
            'ride' => $this->mobileDataService->serializeRide($ride->fresh(['passenger', 'driver.driverProfile'])),
        ];
    }

    private function applyReliabilityPenalty(User $user): void
    {
        $newScore = max(10, ((int) $user->trust_score) - 10);

        $user->forceFill([
            'trust_score' => $newScore,
            'driver_status' => DriverStatus::Online,
        ])->save();

        if ($newScore < 50) {
            $this->downgradeDriverTariff($user);
        }
    }

    private function downgradeDriverTariff(User $user): void
    {
        $profile = $user->driverProfile()->first();

        if (! $profile) {
            return;
        }

        $currentTariff = $profile->car_tariff instanceof TariffType
            ? $profile->car_tariff
            : TariffType::tryFrom((string) $profile->car_tariff);

        $downgradedTariff = match ($currentTariff) {
            TariffType::Business => TariffType::Comfort,
            TariffType::Comfort => TariffType::Economy,
            default => null,
        };

        if ($downgradedTariff) {
            $profile->forceFill([
                'car_tariff' => $downgradedTariff,
            ])->save();
        }
    }

    private function applyRideEligibilityFilter($query, User $user): void
    {
        $profile = $user->driverProfile()->first();
        $carTariff = $profile?->car_tariff;
        $acceptsDelivery = $profile?->accepts_delivery ?? true;

        $query->where(function ($eligible) use ($carTariff, $acceptsDelivery) {
            $eligible->where(function ($taxiQuery) use ($carTariff) {
                $taxiQuery->where('mode', OrderMode::Taxi->value);

                if ($carTariff instanceof TariffType) {
                    $taxiQuery->where('tariff', $carTariff->value);
                }
            });

            if ($acceptsDelivery) {
                $eligible->orWhere('mode', OrderMode::Delivery->value);
            }
        });
    }

    private function driverCanAcceptRide(User $user, Ride $ride): bool
    {
        $profile = $user->driverProfile()->first();

        if (! $profile) {
            return $ride->mode !== OrderMode::Delivery;
        }

        if ($ride->mode === OrderMode::Delivery) {
            return (bool) $profile->accepts_delivery;
        }

        if (! ($profile->car_tariff instanceof TariffType)) {
            return true;
        }

        return ($ride->tariff instanceof TariffType ? $ride->tariff->value : (string) $ride->tariff) === $profile->car_tariff->value;
    }
}
