<?php

namespace App\Services\Admin;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class DriverService
{
    public function query(?string $search = null): Builder
    {
        return User::query()
            ->with('driverProfile')
            ->where('role', UserRole::Driver->value)
            ->when($search, function (Builder $query, string $search) {
                $term = trim($search);

                $query->where(function (Builder $inner) use ($term) {
                    $inner->where('name', 'like', '%' . $term . '%')
                        ->orWhere('phone', 'like', '%' . preg_replace('/\D+/', '', $term) . '%')
                        ->orWhereHas('driverProfile', function (Builder $profile) use ($term) {
                            $profile->where('first_name', 'like', '%' . $term . '%')
                                ->orWhere('last_name', 'like', '%' . $term . '%')
                                ->orWhereRaw("concat_ws(' ', first_name, last_name) like ?", ['%' . $term . '%'])
                                ->orWhere('car_number', 'like', '%' . $term . '%');
                        });
                });
            })
            ->orderByDesc('id');
    }
}
