<?php

namespace App\Services\Admin;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserService
{
    public function query(?string $phone = null): Builder
    {
        return User::query()
            ->with('driverProfile')
            ->when($phone, fn (Builder $query) => $query->where('phone', 'like', '%' . preg_replace('/\D+/', '', $phone) . '%'))
            ->orderByDesc('id');
    }

    public function find(int $id): User
    {
        return User::query()->with('driverProfile')->findOrFail($id);
    }
}
