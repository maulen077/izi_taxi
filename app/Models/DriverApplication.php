<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverApplication extends Model
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
        'license_path',
        'id_document_path',
        'vehicle_registration_path',
        'car_photo_front',
        'car_photo_side',
        'car_photo_interior',
        'status',
        'submitted_at',
        'reviewed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'car_year' => 'integer',
            'status' => ApplicationStatus::class,
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
