<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'address',
        'is_recent',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_recent' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
