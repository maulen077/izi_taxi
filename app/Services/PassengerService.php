<?php

namespace App\Services;

use App\Enums\OrderMode;
use App\Enums\RideStatus;
use App\Enums\TariffType;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\SavedAddress;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class PassengerService
{
    public function __construct(
        private readonly MobileDataService $mobileDataService,
    ) {
    }

    public function home(User $user): array
    {
        $recentAddresses = SavedAddress::query()
            ->where('user_id', $user->id)
            ->where('is_recent', true)
            ->orderBy('sort_order')
            ->get();

        $activeRide = Ride::query()
            ->where('passenger_id', $user->id)
            ->whereIn('status', [
                RideStatus::Searching,
                RideStatus::Accepted,
                RideStatus::Arriving,
                RideStatus::PickedUp,
                RideStatus::InProgress,
            ])
            ->latest()
            ->first();

        return [
            'user' => $this->mobileDataService->serializeUser($user->fresh(['driverProfile'])),
            'recent_addresses' => $recentAddresses->map(fn (SavedAddress $address) => $this->mobileDataService->serializeSavedAddress($address))->values(),
            'active_ride' => $activeRide ? $this->mobileDataService->serializeRide($activeRide) : null,
            'statistics' => [
                'ride_count' => Ride::query()->where('passenger_id', $user->id)->count(),
                'completed_count' => Ride::query()->where('passenger_id', $user->id)->where('status', RideStatus::Completed->value)->count(),
            ],
        ];
    }

    public function history(User $user): array
    {
        $rides = Ride::query()
            ->with(['driver.driverProfile', 'passenger'])
            ->where('passenger_id', $user->id)
            ->whereIn('status', [RideStatus::Completed->value, RideStatus::Cancelled->value])
            ->latest('completed_at')
            ->get();

        return [
            'rides' => $rides->map(fn (Ride $ride) => $this->mobileDataService->serializeRide($ride))->values(),
        ];
    }

    public function createRide(User $user, array $data): array
    {
        if (! $user->role instanceof UserRole || $user->role !== UserRole::Passenger) {
            throw ValidationException::withMessages([
                'role' => 'Passenger role required.',
            ]);
        }

        $mode = ($data['order_mode'] ?? OrderMode::Taxi->value) === OrderMode::Delivery->value
            ? OrderMode::Delivery
            : OrderMode::Taxi;

        $tariff = TariffType::tryFrom($data['tariff'] ?? TariffType::Economy->value) ?? TariffType::Economy;
        $distance = (float) ($data['distance_km'] ?? 3.2);
        $duration = (int) ($data['duration_minutes'] ?? 12);
        $hasLuggage = (bool) ($data['has_luggage'] ?? false);
        $luggageSize = $data['luggage_size'] ?? null;

        $basePrice = $this->calculatePrice($mode, $tariff, $distance);

        if ($mode === OrderMode::Taxi && $hasLuggage) {
            $basePrice = $luggageSize === 'large' ? $basePrice * 2 : $basePrice + 500;
        }

        $ride = Ride::create([
            'passenger_id' => $user->id,
            'mode' => $mode,
            'tariff' => $tariff,
            'status' => RideStatus::Searching,
            'pickup_address' => $data['pickup'] ?? 'Текущее местоположение',
            'destination_address' => ($data['skip_destination'] ?? false) ? null : ($data['destination'] ?? null),
            'price' => $basePrice,
            'base_price' => $basePrice,
            'distance_km' => $distance,
            'duration_minutes' => $duration,
            'has_luggage' => $hasLuggage,
            'luggage_size' => $luggageSize,
            'sender_phone' => $data['sender_phone'] ?? null,
            'receiver_phone' => $data['receiver_phone'] ?? null,
            'notes' => $data['notes'] ?? null,
            'waiting_minutes' => 0,
        ]);

        return [
            'ride' => $this->mobileDataService->serializeRide($ride->fresh(['passenger', 'driver.driverProfile'])),
        ];
    }

    public function showRide(User $user, Ride $ride): array
    {
        if ($ride->passenger_id !== $user->id) {
            throw ValidationException::withMessages([
                'ride' => 'Ride not found.',
            ]);
        }

        return [
            'ride' => $this->mobileDataService->serializeRide($ride->loadMissing(['passenger', 'driver.driverProfile'])),
        ];
    }

    public function rateRide(User $user, Ride $ride, array $data): array
    {
        if ($ride->passenger_id !== $user->id) {
            throw ValidationException::withMessages([
                'ride' => 'Ride not found.',
            ]);
        }

        $ride->forceFill([
            'passenger_rating' => (int) ($data['rating'] ?? 0),
            'comment' => $data['comment'] ?? null,
        ])->save();

        if ($ride->driver_id) {
            $ride->driver->forceFill([
                'trust_score' => min(100, ($ride->driver->trust_score ?? 0) + max(0, (int) ($data['rating'] ?? 0))),
            ])->save();
        }

        return [
            'ride' => $this->mobileDataService->serializeRide($ride->fresh(['passenger', 'driver.driverProfile'])),
        ];
    }

    private function calculatePrice(OrderMode $mode, TariffType $tariff, float $distance): int
    {
        if ($mode === OrderMode::Delivery) {
            return (int) round($distance * 200);
        }

        return match ($tariff) {
            TariffType::Economy => 1200,
            TariffType::Comfort => 1600,
            TariffType::Business => 2500,
            TariffType::Minivan => 2000,
        };
    }
}
