<?php

namespace App\Models;

use App\Enums\DriverStatus;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'role',
        'language',
        'driver_status',
        'balance',
        'trust_score',
        'avatar_url',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'driver_status' => DriverStatus::class,
            'balance' => 'integer',
            'trust_score' => 'integer',
        ];
    }

    public function driverProfile(): HasOne
    {
        return $this->hasOne(DriverProfile::class);
    }

    public function currentLocation(): HasOne
    {
        return $this->hasOne(DriverLocation::class);
    }

    public function ridesAsPassenger(): HasMany
    {
        return $this->hasMany(Ride::class, 'passenger_id');
    }

    public function ridesAsDriver(): HasMany
    {
        return $this->hasMany(Ride::class, 'driver_id');
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function driverApplications(): HasMany
    {
        return $this->hasMany(DriverApplication::class);
    }

    public function savedAddresses(): HasMany
    {
        return $this->hasMany(SavedAddress::class);
    }
}
