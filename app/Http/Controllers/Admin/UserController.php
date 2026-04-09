<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DriverStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {
    }

    public function index(Request $request): View
    {
        $users = $this->userService->query($request->string('search')->toString())->paginate(20)->withQueryString();

        return view('admin.pages.users.index', compact('users'));
    }

    public function detail(User $user): View
    {
        $user->load('driverProfile');

        $ridesAsPassenger = Ride::query()->where('passenger_id', $user->id)->latest()->limit(10)->get();
        $ridesAsDriver = Ride::query()->where('driver_id', $user->id)->latest()->limit(10)->get();

        return view('admin.pages.users.detail', compact('user', 'ridesAsPassenger', 'ridesAsDriver'));
    }

    public function edit(User $user): View
    {
        $user->load('driverProfile');

        return view('admin.pages.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30', Rule::unique('users', 'phone')->ignore($user->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(array_map(fn (UserRole $role) => $role->value, UserRole::cases()))],
            'language' => ['nullable', 'string', 'max:10'],
            'driver_status' => ['nullable', 'string', Rule::in(array_map(fn (DriverStatus $status) => $status->value, DriverStatus::cases()))],
            'balance' => ['nullable', 'numeric'],
            'trust_score' => ['nullable', 'numeric', 'min:10', 'max:100'],
            'avatar_url' => ['nullable', 'url'],
            'password' => ['nullable', 'string', 'min:6'],
            'driver_first_name' => ['nullable', 'string', 'max:255'],
            'driver_last_name' => ['nullable', 'string', 'max:255'],
            'car_brand' => ['nullable', 'string', 'max:255'],
            'car_model' => ['nullable', 'string', 'max:255'],
            'car_year' => ['nullable', 'integer', 'min:1950', 'max:' . ((int) date('Y') + 1)],
            'car_number' => ['nullable', 'string', 'max:50'],
            'car_color' => ['nullable', 'string', 'max:100'],
        ]);

        $user->forceFill([
            'name' => $data['name'],
            'phone' => preg_replace('/\D+/', '', $data['phone']),
            'email' => $data['email'] ?: null,
            'role' => $data['role'],
            'language' => $data['language'] ?? $user->language,
            'driver_status' => $data['driver_status'] ?? $user->driver_status,
            'balance' => $data['balance'] ?? $user->balance,
            'trust_score' => $data['trust_score'] ?? $user->trust_score,
            'avatar_url' => $data['avatar_url'] ?? $user->avatar_url,
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if ($data['role'] === UserRole::Driver->value) {
            $user->driverProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $data['driver_first_name'] ?? null,
                    'last_name' => $data['driver_last_name'] ?? null,
                    'car_brand' => $data['car_brand'] ?? null,
                    'car_model' => $data['car_model'] ?? null,
                    'car_year' => $data['car_year'] ?? null,
                    'car_number' => $data['car_number'] ?? null,
                    'car_color' => $data['car_color'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.users')->with('success', 'Пользователь обновлён.');
    }
}
