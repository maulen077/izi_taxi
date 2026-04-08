<?php

namespace App\Services;

use App\Models\User;

class SettingsService
{
    public function __construct(
        private readonly MobileDataService $mobileDataService,
    ) {
    }

    public function languages(): array
    {
        return [
            'languages' => $this->mobileDataService->languages(),
        ];
    }

    public function updateLanguage(User $user, array $data): array
    {
        $allowed = collect($this->mobileDataService->languages())->pluck('code')->all();
        $code = $data['language'] ?? 'ru';

        if (! in_array($code, $allowed, true)) {
            $code = 'ru';
        }

        $user->forceFill([
            'language' => $code,
        ])->save();

        return [
            'user' => $this->mobileDataService->serializeUser($user->fresh(['driverProfile'])),
        ];
    }
}
