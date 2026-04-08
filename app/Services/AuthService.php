<?php

namespace App\Services;

use App\Enums\DriverStatus;
use App\Enums\UserRole;
use App\Models\DriverProfile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private readonly MobileDataService $mobileDataService,
    ) {
    }

    public function register(array $data): array
    {
        $phone = $this->mobileDataService->normalizePhone($data['phone']);

        if (User::query()->where('phone', $phone)->exists()) {
            throw ValidationException::withMessages([
                'phone' => 'The phone has already been taken.',
            ]);
        }

        $role = ($data['role'] ?? UserRole::Passenger->value) === UserRole::Driver->value
            ? UserRole::Driver
            : UserRole::Passenger;

        $user = User::create([
            'name' => $data['name'] ?? 'Новый пользователь',
            'phone' => $phone,
            'email' => $data['email'] ?? null,
            'role' => $role,
            'language' => $data['language'] ?? 'ru',
            'driver_status' => DriverStatus::Offline,
            'balance' => 0,
            'trust_score' => 100,
            'password' => $data['password'],
        ]);

        if ($role === UserRole::Driver) {
            DriverProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => trim(explode(' ', $user->name)[0] ?? $user->name),
                    'last_name' => trim(explode(' ', $user->name)[1] ?? ''),
                    'phone' => $phone,
                    'email' => $user->email,
                    'application_status' => 'approved',
                    'submitted_at' => now(),
                    'approved_at' => now(),
                ]
            );
        }

        return $this->issueTokenResponse($user);
    }

    public function login(array $data): array
    {
        $phone = $this->mobileDataService->normalizePhone($data['phone']);
        $password = $data['password'] ?? '';

        $user = User::query()
            ->with('driverProfile')
            ->where('phone', $phone)
            ->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'phone' => 'Invalid credentials.',
            ]);
        }

        return $this->issueTokenResponse($user);
    }

    public function resetPassword(array $data): array
    {
        $phone = $this->mobileDataService->normalizePhone($data['phone']);
        $password = $data['password'] ?? 'password';

        $user = User::query()->where('phone', $phone)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'phone' => 'The phone was not found.',
            ]);
        }

        $user->forceFill([
            'password' => $password,
        ])->save();

        return [
            'message' => 'Password reset successfully.',
            'user' => $this->mobileDataService->serializeUser($user->fresh(['driverProfile'])),
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }

    private function issueTokenResponse(User $user): array
    {
        $token = $user->createToken('mobile');

        return [
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'user' => $this->mobileDataService->serializeUser($user->fresh(['driverProfile'])),
        ];
    }
}
