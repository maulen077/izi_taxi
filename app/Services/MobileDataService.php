<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\DriverStatus;
use App\Enums\OrderMode;
use App\Enums\RideStatus;
use App\Enums\SupportSubject;
use App\Enums\TariffType;
use App\Enums\UserRole;
use App\Models\DriverApplication;
use App\Models\DriverProfile;
use App\Models\Ride;
use App\Models\SavedAddress;
use App\Models\SupportTicket;
use App\Models\User;

class MobileDataService
{
    public function __construct(
        private readonly PricingSettingsService $pricingSettingsService,
    ) {
    }

    public function normalizePhone(?string $phone): string
    {
        return preg_replace('/\D+/', '', (string) $phone) ?? '';
    }

    public function formatPhone(?string $phone): string
    {
        $phone = $this->normalizePhone($phone);

        if ($phone === '') {
            return '';
        }

        if (strlen($phone) === 10) {
            return sprintf('+7 (%s) %s-%s-%s', substr($phone, 0, 3), substr($phone, 3, 3), substr($phone, 6, 2), substr($phone, 8, 2));
        }

        if (strlen($phone) === 11) {
            return sprintf('+%s (%s) %s-%s-%s', substr($phone, 0, 1), substr($phone, 1, 3), substr($phone, 4, 3), substr($phone, 7, 2), substr($phone, 9, 2));
        }

        return '+' . $phone;
    }

    public function languages(): array
    {
        return [
            ['code' => 'ru', 'name' => 'Russian', 'native_name' => 'Русский'],
            ['code' => 'kk', 'name' => 'Kazakh', 'native_name' => 'Қазақша'],
        ];
    }

    public function tariffs(): array
    {
        $taxiRatePerKm = $this->pricingSettingsService->taxiRatePerKm();
        $deliveryRatePerKm = $this->pricingSettingsService->deliveryRatePerKm();

        return [
            TariffType::Economy->value => [
                'name' => 'Эконом',
                'mode' => OrderMode::Taxi->value,
                'base_price' => 1200,
                'taxi_rate_per_km' => $taxiRatePerKm,
                'delivery_rate_per_km' => $deliveryRatePerKm,
                'time' => 2,
                'seats' => 4,
                'emoji' => '🚗',
            ],
            TariffType::Comfort->value => [
                'name' => 'Комфорт',
                'mode' => OrderMode::Taxi->value,
                'base_price' => 1600,
                'taxi_rate_per_km' => $taxiRatePerKm,
                'delivery_rate_per_km' => $deliveryRatePerKm,
                'time' => 2,
                'seats' => 4,
                'emoji' => '🚙',
            ],
            TariffType::Business->value => [
                'name' => 'Бизнес',
                'mode' => OrderMode::Taxi->value,
                'base_price' => 2500,
                'taxi_rate_per_km' => $taxiRatePerKm,
                'delivery_rate_per_km' => $deliveryRatePerKm,
                'time' => 3,
                'seats' => 4,
                'emoji' => '🚘',
            ],
            TariffType::Minivan->value => [
                'name' => 'Минивэн',
                'mode' => OrderMode::Taxi->value,
                'base_price' => 2000,
                'taxi_rate_per_km' => $taxiRatePerKm,
                'delivery_rate_per_km' => $deliveryRatePerKm,
                'time' => 3,
                'seats' => 7,
                'emoji' => '🚐',
            ],
        ];
    }

    public function supportSubjects(): array
    {
        return [
            ['code' => SupportSubject::Complaint->value, 'name' => 'Жалоба на водителя'],
            ['code' => SupportSubject::Technical->value, 'name' => 'Технические проблемы'],
            ['code' => SupportSubject::Payment->value, 'name' => 'Вопросы по оплате'],
            ['code' => SupportSubject::LostItem->value, 'name' => 'Забытые вещи'],
            ['code' => SupportSubject::Other->value, 'name' => 'Другое'],
        ];
    }

    public function driverStatuses(): array
    {
        return array_map(
            fn (DriverStatus $status) => [
                'code' => $status->value,
                'label' => $status->label(),
            ],
            DriverStatus::cases()
        );
    }

    public function rideStatuses(): array
    {
        return array_map(
            fn (RideStatus $status) => [
                'code' => $status->value,
                'passenger_label' => $status->passengerLabel(),
                'driver_label' => $status->driverLabel(),
            ],
            RideStatus::cases()
        );
    }

    public function serializeUser(User $user): array
    {
        $user->loadMissing(['driverProfile']);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'formatted_phone' => $this->formatPhone($user->phone),
            'email' => $user->email,
            'role' => $user->role instanceof UserRole ? $user->role->value : (string) $user->role,
            'language' => $user->language,
            'driver_status' => $user->driver_status instanceof DriverStatus ? $user->driver_status->value : (string) $user->driver_status,
            'balance' => $user->balance,
            'trust_score' => $user->trust_score,
            'avatar_url' => $user->avatar_url,
            'driver_profile' => $user->driverProfile ? $this->serializeDriverProfile($user->driverProfile) : null,
        ];
    }

    public function serializeDriverProfile(DriverProfile $profile): array
    {
        return [
            'id' => $profile->id,
            'user_id' => $profile->user_id,
            'first_name' => $profile->first_name,
            'last_name' => $profile->last_name,
            'phone' => $profile->phone,
            'formatted_phone' => $this->formatPhone($profile->phone),
            'email' => $profile->email,
            'car_brand' => $profile->car_brand,
            'car_model' => $profile->car_model,
            'car_year' => $profile->car_year,
            'car_number' => $profile->car_number,
            'car_color' => $profile->car_color,
            'car_tariff' => $profile->car_tariff instanceof TariffType ? $profile->car_tariff->value : (string) $profile->car_tariff,
            'accepts_delivery' => (bool) $profile->accepts_delivery,
            'car_photo_front' => $profile->car_photo_front,
            'car_photo_side' => $profile->car_photo_side,
            'car_photo_interior' => $profile->car_photo_interior,
            'license_path' => $profile->license_path,
            'id_document_path' => $profile->id_document_path,
            'vehicle_registration_path' => $profile->vehicle_registration_path,
            'application_status' => $profile->application_status instanceof ApplicationStatus ? $profile->application_status->value : (string) $profile->application_status,
            'submitted_at' => optional($profile->submitted_at)?->toIso8601String(),
            'approved_at' => optional($profile->approved_at)?->toIso8601String(),
            'notes' => $profile->notes,
        ];
    }

    public function serializeRide(Ride $ride): array
    {
        $ride->loadMissing(['passenger.driverProfile', 'driver.driverProfile', 'driver.currentLocation']);

        $passenger = $ride->passenger;
        $driver = $ride->driver;

        return [
            'id' => $ride->id,
            'mode' => $ride->mode instanceof OrderMode ? $ride->mode->value : (string) $ride->mode,
            'tariff' => $ride->tariff instanceof TariffType ? $ride->tariff->value : (string) $ride->tariff,
            'status' => $ride->status instanceof RideStatus ? $ride->status->value : (string) $ride->status,
            'status_label' => $ride->status instanceof RideStatus ? $ride->status->passengerLabel() : (string) $ride->status,
            'pickup' => $ride->pickup_address,
            'pickup_location' => $this->serializePoint($ride->pickup_lat, $ride->pickup_lng, [
                'address' => $ride->pickup_address,
            ]),
            'destination' => $ride->destination_address,
            'destination_location' => $this->serializePoint($ride->destination_lat, $ride->destination_lng, [
                'address' => $ride->destination_address,
            ]),
            'price' => $ride->price,
            'base_price' => $ride->base_price,
            'distance' => (float) $ride->distance_km,
            'duration' => $ride->duration_minutes,
            'has_luggage' => (bool) $ride->has_luggage,
            'luggage_size' => $ride->luggage_size,
            'sender_phone' => $ride->sender_phone,
            'receiver_phone' => $ride->receiver_phone,
            'notes' => $ride->notes,
            'waiting_minutes' => $ride->waiting_minutes,
            'passenger_rating' => $ride->passenger_rating,
            'comment' => $ride->comment,
            'passenger' => $passenger ? [
                'id' => $passenger->id,
                'name' => $passenger->name,
                'phone' => $passenger->phone,
                'formatted_phone' => $this->formatPhone($passenger->phone),
                'rating' => $passenger->trust_score,
            ] : null,
            'driver' => $driver ? [
                'id' => $driver->id,
                'name' => $driver->name,
                'phone' => $driver->phone,
                'formatted_phone' => $this->formatPhone($driver->phone),
                'rating' => $driver->trust_score,
                'car' => $driver->driverProfile ? trim(($driver->driverProfile->car_brand ?? '') . ' ' . ($driver->driverProfile->car_model ?? '')) : null,
                'plate' => $driver->driverProfile?->car_number,
                'location' => $driver->currentLocation ? $this->serializeDriverLocation($driver) : null,
            ] : null,
            'accepted_at' => optional($ride->accepted_at)?->toIso8601String(),
            'arrived_at' => optional($ride->arrived_at)?->toIso8601String(),
            'started_at' => optional($ride->started_at)?->toIso8601String(),
            'completed_at' => optional($ride->completed_at)?->toIso8601String(),
            'created_at' => optional($ride->created_at)?->toIso8601String(),
        ];
    }

    public function serializeDriverLocation(User $driver): ?array
    {
        $driver->loadMissing('currentLocation');
        $location = $driver->currentLocation;

        if (! $location) {
            return null;
        }

        return $this->serializePoint($location->lat, $location->lng, [
            'heading' => $location->heading !== null ? (float) $location->heading : null,
            'accuracy' => $location->accuracy !== null ? (float) $location->accuracy : null,
            'updated_at' => optional($location->updated_at)?->toIso8601String(),
        ]);
    }

    public function serializeRideTracking(Ride $ride): array
    {
        $ride->loadMissing(['driver.currentLocation', 'driver.driverProfile', 'passenger']);

        $status = $ride->status instanceof RideStatus ? $ride->status : RideStatus::tryFrom((string) $ride->status);
        $pickup = $this->serializePoint($ride->pickup_lat, $ride->pickup_lng, [
            'address' => $ride->pickup_address,
        ]);
        $destination = $this->serializePoint($ride->destination_lat, $ride->destination_lng, [
            'address' => $ride->destination_address,
        ]);
        $driver = $ride->driver ? $this->serializeDriverLocation($ride->driver) : null;

        return [
            'ride_id' => $ride->id,
            'status' => $status?->value ?? (string) $ride->status,
            'pickup' => $pickup,
            'destination' => $destination,
            'driver' => $driver,
            'route_points' => $this->buildRideRoutePoints($status, $pickup, $destination, $driver),
        ];
    }

    public function serializeSavedAddress(SavedAddress $address): array
    {
        return [
            'id' => $address->id,
            'label' => $address->label,
            'address' => $address->address,
            'is_recent' => $address->is_recent,
            'sort_order' => $address->sort_order,
        ];
    }

    public function serializeSupportTicket(SupportTicket $ticket): array
    {
        return [
            'id' => $ticket->id,
            'subject' => $ticket->subject instanceof SupportSubject ? $ticket->subject->value : (string) $ticket->subject,
            'message' => $ticket->message,
            'status' => $ticket->status,
            'contact_phone' => $ticket->contact_phone,
            'contact_email' => $ticket->contact_email,
            'resolved_at' => optional($ticket->resolved_at)?->toIso8601String(),
            'created_at' => optional($ticket->created_at)?->toIso8601String(),
        ];
    }

    public function serializeDriverApplication(DriverApplication $application): array
    {
        return [
            'id' => $application->id,
            'first_name' => $application->first_name,
            'last_name' => $application->last_name,
            'phone' => $application->phone,
            'email' => $application->email,
            'car_brand' => $application->car_brand,
            'car_model' => $application->car_model,
            'car_year' => $application->car_year,
            'car_number' => $application->car_number,
            'car_color' => $application->car_color,
            'license_path' => $application->license_path,
            'id_document_path' => $application->id_document_path,
            'vehicle_registration_path' => $application->vehicle_registration_path,
            'car_photo_front' => $application->car_photo_front,
            'car_photo_side' => $application->car_photo_side,
            'car_photo_interior' => $application->car_photo_interior,
            'status' => $application->status instanceof ApplicationStatus ? $application->status->value : (string) $application->status,
            'submitted_at' => optional($application->submitted_at)?->toIso8601String(),
            'reviewed_at' => optional($application->reviewed_at)?->toIso8601String(),
            'notes' => $application->notes,
        ];
    }

    private function serializePoint(float|string|null $lat, float|string|null $lng, array $extra = []): ?array
    {
        if ($lat === null || $lng === null) {
            return null;
        }

        return array_merge([
            'lat' => (float) $lat,
            'lng' => (float) $lng,
        ], $extra);
    }

    private function buildRideRoutePoints(?RideStatus $status, ?array $pickup, ?array $destination, ?array $driver): array
    {
        $points = [];

        if ($status === RideStatus::PickedUp || $status === RideStatus::InProgress || $status === RideStatus::Completed) {
            if ($driver) {
                $points[] = $this->routePoint($driver);
            }
            if ($destination) {
                $points[] = $this->routePoint($destination);
            }

            return array_values(array_filter($points));
        }

        if ($driver) {
            $points[] = $this->routePoint($driver);
        }
        if ($pickup) {
            $points[] = $this->routePoint($pickup);
        }
        if ($destination) {
            $points[] = $this->routePoint($destination);
        }

        return array_values(array_filter($points));
    }

    private function routePoint(?array $point): ?array
    {
        if (! $point || ! isset($point['lat'], $point['lng'])) {
            return null;
        }

        return [
            'lat' => (float) $point['lat'],
            'lng' => (float) $point['lng'],
        ];
    }
}
