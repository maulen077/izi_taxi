<?php

namespace App\Services;

use App\Models\Setting;

class PricingSettingsService
{
    public const TAXI_RATE_PER_KM = 'taxi_rate_per_km';
    public const DELIVERY_RATE_PER_KM = 'delivery_rate_per_km';

    private const DEFAULT_RATES = [
        self::TAXI_RATE_PER_KM => 0,
        self::DELIVERY_RATE_PER_KM => 200,
    ];

    public function all(): array
    {
        $stored = Setting::query()
            ->whereIn('key', array_keys(self::DEFAULT_RATES))
            ->pluck('value', 'key');

        $rates = [];

        foreach (self::DEFAULT_RATES as $key => $defaultValue) {
            $rates[$key] = (int) ($stored[$key] ?? $defaultValue);
        }

        return $rates;
    }

    public function taxiRatePerKm(): int
    {
        return $this->all()[self::TAXI_RATE_PER_KM];
    }

    public function deliveryRatePerKm(): int
    {
        return $this->all()[self::DELIVERY_RATE_PER_KM];
    }

    public function update(array $rates): array
    {
        $payload = [
            self::TAXI_RATE_PER_KM => (int) ($rates[self::TAXI_RATE_PER_KM] ?? self::DEFAULT_RATES[self::TAXI_RATE_PER_KM]),
            self::DELIVERY_RATE_PER_KM => (int) ($rates[self::DELIVERY_RATE_PER_KM] ?? self::DEFAULT_RATES[self::DELIVERY_RATE_PER_KM]),
        ];

        foreach ($payload as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => (string) $value],
            );
        }

        return $payload;
    }
}
