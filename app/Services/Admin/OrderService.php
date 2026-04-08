<?php

namespace App\Services\Admin;

use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class OrderService
{
    public function query(array $filters = []): Builder
    {
        return Ride::query()
            ->when($filters['phone'] ?? null, function (Builder $query, string $phone) {
                $digits = preg_replace('/\D+/', '', $phone);

                $query->where('contact_phone', 'like', '%' . $digits . '%');
            })
            ->when($filters['date_from'] ?? null, fn (Builder $query, string $date) => $query->whereDate('created_at', '>=', Carbon::parse($date)->toDateString()))
            ->when($filters['date_to'] ?? null, fn (Builder $query, string $date) => $query->whereDate('created_at', '<=', Carbon::parse($date)->toDateString()))
            ->orderByDesc('id');
    }

    public function create(array $data, User $creator): Ride
    {
        $phone = preg_replace('/\D+/', '', $data['contact_phone']);
        $passenger = User::query()->where('phone', $phone)->first();

        return Ride::unguarded(function () use ($data, $creator, $phone, $passenger) {
            return Ride::query()->create([
                'passenger_id' => $passenger?->id,
                'driver_id' => null,
                'created_by_user_id' => $creator->id,
                'contact_phone' => $phone,
                'passenger_name' => $data['passenger_name'] ?? $passenger?->name,
                'pickup_address' => $data['pickup_address'],
                'dropoff_address' => $data['dropoff_address'],
                'price' => $data['price'] ?? 0,
                'status' => 'searching',
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }
}
