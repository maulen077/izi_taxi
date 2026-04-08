<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Enums\DriverStatus;
use App\Enums\OrderMode;
use App\Enums\RideStatus;
use App\Enums\TariffType;
use App\Enums\UserRole;
use App\Models\DriverApplication;
use App\Models\DriverProfile;
use App\Models\Ride;
use App\Models\SavedAddress;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $passenger = User::updateOrCreate(
            ['phone' => '7771234567'],
            [
                'name' => 'Асель М.',
                'email' => 'asel@example.test',
                'role' => UserRole::Passenger,
                'language' => 'ru',
                'driver_status' => DriverStatus::Offline,
                'balance' => 0,
                'trust_score' => 96,
                'password' => Hash::make('password'),
            ]
        );

        $driver = User::updateOrCreate(
            ['phone' => '7477471998'],
            [
                'name' => 'Ержан С.',
                'email' => 'driver@example.test',
                'role' => UserRole::Driver,
                'language' => 'ru',
                'driver_status' => DriverStatus::Offline,
                'balance' => 2560000,
                'trust_score' => 87,
                'password' => Hash::make('qwerty123'),
            ]
        );

        DriverProfile::updateOrCreate(
            ['user_id' => $driver->id],
            [
                'first_name' => 'Ержан',
                'last_name' => 'Сейтов',
                'phone' => '7477471998',
                'email' => 'driver@example.test',
                'car_brand' => 'Toyota',
                'car_model' => 'Camry',
                'car_year' => 2020,
                'car_number' => '123 ABC 01',
                'car_color' => 'Черный',
                'car_tariff' => TariffType::Comfort,
                'application_status' => ApplicationStatus::Approved,
                'submitted_at' => now(),
                'approved_at' => now(),
            ]
        );

        SavedAddress::query()->delete();
        SavedAddress::insert([
            ['user_id' => $passenger->id, 'label' => 'Dostyk Plaza', 'address' => 'пр. Достык, 111, Алматы', 'is_recent' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $passenger->id, 'label' => 'Mega Park', 'address' => 'пр. Розыбакиева, 247, Алматы', 'is_recent' => true, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Ride::query()->delete();
        Ride::insert([
            [
                'passenger_id' => $passenger->id,
                'driver_id' => $driver->id,
                'mode' => OrderMode::Taxi->value,
                'tariff' => TariffType::Economy->value,
                'status' => RideStatus::Completed->value,
                'pickup_address' => 'пр. Абая, 123, Алматы',
                'destination_address' => 'ул. Достык, 200, Алматы',
                'price' => 1500,
                'base_price' => 1500,
                'distance_km' => 3.2,
                'duration_minutes' => 12,
                'has_luggage' => false,
                'waiting_minutes' => 0,
                'passenger_rating' => 5,
                'comment' => 'Быстро и удобно',
                'accepted_at' => now()->subDays(2)->setTime(14, 28),
                'arrived_at' => now()->subDays(2)->setTime(14, 31),
                'started_at' => now()->subDays(2)->setTime(14, 34),
                'completed_at' => now()->subDays(2)->setTime(14, 42),
                'created_at' => now()->subDays(2)->setTime(14, 20),
                'updated_at' => now()->subDays(2)->setTime(14, 42),
            ],
            [
                'passenger_id' => $passenger->id,
                'driver_id' => $driver->id,
                'mode' => OrderMode::Taxi->value,
                'tariff' => TariffType::Comfort->value,
                'status' => RideStatus::Completed->value,
                'pickup_address' => 'ул. Панфилова, 15, Алматы',
                'destination_address' => 'пр. Назарбаева, 50, Астана',
                'price' => 12000,
                'base_price' => 12000,
                'distance_km' => 18.4,
                'duration_minutes' => 38,
                'has_luggage' => true,
                'luggage_size' => 'large',
                'waiting_minutes' => 2,
                'passenger_rating' => 4,
                'comment' => 'Долгая дорога, но все нормально',
                'accepted_at' => now()->subDays(3)->setTime(12, 12),
                'arrived_at' => now()->subDays(3)->setTime(12, 20),
                'started_at' => now()->subDays(3)->setTime(12, 24),
                'completed_at' => now()->subDays(3)->setTime(12, 58),
                'created_at' => now()->subDays(3)->setTime(12, 5),
                'updated_at' => now()->subDays(3)->setTime(12, 58),
            ],
            [
                'passenger_id' => $passenger->id,
                'driver_id' => null,
                'mode' => OrderMode::Taxi->value,
                'tariff' => TariffType::Comfort->value,
                'status' => RideStatus::Searching->value,
                'pickup_address' => 'пр. Абая, 150, Алматы',
                'destination_address' => 'Dostyk Plaza, пр. Достык, 111, Алматы',
                'price' => 2500,
                'base_price' => 2500,
                'distance_km' => 3.2,
                'duration_minutes' => 12,
                'has_luggage' => true,
                'luggage_size' => 'large',
                'waiting_minutes' => 0,
                'created_at' => now()->subMinutes(5),
                'updated_at' => now()->subMinutes(5),
            ],
        ]);

        DriverApplication::query()->delete();
        DriverApplication::create([
            'user_id' => $driver->id,
            'first_name' => 'Ержан',
            'last_name' => 'Сейтов',
            'phone' => '7477471998',
            'email' => 'driver@example.test',
            'car_brand' => 'Toyota',
            'car_model' => 'Camry',
            'car_year' => 2020,
            'car_number' => '123 ABC 01',
            'car_color' => 'Черный',
            'status' => ApplicationStatus::Approved,
            'submitted_at' => now()->subDay(),
            'reviewed_at' => now()->subDay(),
        ]);
    }
}
