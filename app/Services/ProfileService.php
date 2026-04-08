<?php

namespace App\Services;

use App\Models\User;

class ProfileService
{
    public function __construct(
        private readonly MobileDataService $mobileDataService,
    ) {
    }

    public function me(User $user): array
    {
        $user->loadMissing(['driverProfile']);

        return [
            'user' => $this->mobileDataService->serializeUser($user),
        ];
    }

    public function update(User $user, array $data): array
    {
        $user->fill(array_filter([
            'name' => $data['name'] ?? null,
            'phone' => isset($data['phone']) ? $this->mobileDataService->normalizePhone($data['phone']) : null,
            'email' => $data['email'] ?? null,
            'language' => $data['language'] ?? null,
            'avatar_url' => $data['avatar_url'] ?? null,
            'password' => $data['password'] ?? null,
        ], fn ($value) => $value !== null && $value !== ''));

        $user->save();

        return [
            'user' => $this->mobileDataService->serializeUser($user->fresh(['driverProfile'])),
        ];
    }
}
