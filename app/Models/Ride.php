<?php

namespace App\Models;

use App\Enums\OrderMode;
use App\Enums\RideStatus;
use App\Enums\TariffType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ride extends Model
{
    use HasFactory;

    protected $fillable = [
        'passenger_id',
        'driver_id',
        'mode',
        'tariff',
        'status',
        'pickup_address',
        'pickup_lat',
        'pickup_lng',
        'destination_address',
        'destination_lat',
        'destination_lng',
        'price',
        'base_price',
        'distance_km',
        'duration_minutes',
        'has_luggage',
        'luggage_size',
        'sender_phone',
        'receiver_phone',
        'notes',
        'waiting_minutes',
        'passenger_rating',
        'comment',
        'accepted_at',
        'arrived_at',
        'started_at',
        'completed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'mode' => OrderMode::class,
            'tariff' => TariffType::class,
            'status' => RideStatus::class,
            'price' => 'integer',
            'base_price' => 'integer',
            'pickup_lat' => 'decimal:7',
            'pickup_lng' => 'decimal:7',
            'destination_lat' => 'decimal:7',
            'destination_lng' => 'decimal:7',
            'distance_km' => 'decimal:2',
            'duration_minutes' => 'integer',
            'has_luggage' => 'boolean',
            'waiting_minutes' => 'integer',
            'passenger_rating' => 'integer',
            'accepted_at' => 'datetime',
            'arrived_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
