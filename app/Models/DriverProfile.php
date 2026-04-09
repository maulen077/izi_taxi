<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use App\Enums\TariffType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'car_brand',
        'car_model',
        'car_year',
        'car_number',
        'car_color',
        'car_tariff',
        'accepts_delivery',
        'car_photo_front',
        'car_photo_side',
        'car_photo_interior',
        'license_path',
        'id_document_path',
        'vehicle_registration_path',
        'application_status',
        'submitted_at',
        'approved_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'car_year' => 'integer',
            'car_tariff' => TariffType::class,
            'accepts_delivery' => 'boolean',
            'application_status' => ApplicationStatus::class,
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
