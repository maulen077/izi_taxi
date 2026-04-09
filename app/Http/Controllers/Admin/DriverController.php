<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DriverStatus;
use App\Enums\TariffType;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Services\Admin\DriverService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DriverController extends Controller
{
    public function __construct(
        private readonly DriverService $driverService
    ) {
    }

    public function index(Request $request): View
    {
        $drivers = $this->driverService->query($request->string('search')->toString())->paginate(20)->withQueryString();

        return view('admin.pages.drivers.index', compact('drivers'));
    }

    public function detail(User $user): View
    {
        $user->load('driverProfile');
        $ridesAsDriver = Ride::query()
            ->with('passenger')
            ->where('driver_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.pages.drivers.detail', [
            'user' => $user,
            'ridesAsDriver' => $ridesAsDriver,
            'tariffOptions' => TariffType::cases(),
        ]);
    }

    public function edit(User $user): View
    {
        $user->load('driverProfile');

        return view('admin.pages.drivers.edit', [
            'user' => $user,
            'tariffOptions' => TariffType::cases(),
        ]);
    }

    public function updateCapabilities(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'car_tariff' => ['required', Rule::in(array_map(fn (TariffType $tariff) => $tariff->value, TariffType::cases()))],
            'accepts_delivery' => ['required', 'boolean'],
        ]);

        $user->load('driverProfile');

        $user->driverProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $user->driverProfile?->phone ?? $user->phone ?? '',
                'email' => $user->driverProfile?->email ?? $user->email,
                'first_name' => $user->driverProfile?->first_name ?? $user->name,
                'last_name' => $user->driverProfile?->last_name ?? '-',
                'car_tariff' => $data['car_tariff'],
                'accepts_delivery' => (bool) $data['accepts_delivery'],
            ]
        );

        return redirect()->route('admin.driver_detail', $user)->with('success', 'Настройки водителя обновлены.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30', Rule::unique('users', 'phone')->ignore($user->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'driver_status' => ['nullable', 'string', Rule::in(array_map(fn (DriverStatus $status) => $status->value, DriverStatus::cases()))],
            'balance' => ['nullable', 'numeric'],
            'trust_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'avatar_url' => ['nullable', 'url'],
            'password' => ['nullable', 'string', 'min:6'],
            'driver_first_name' => ['nullable', 'string', 'max:255'],
            'driver_last_name' => ['nullable', 'string', 'max:255'],
            'car_brand' => ['nullable', 'string', 'max:255'],
            'car_model' => ['nullable', 'string', 'max:255'],
            'car_year' => ['nullable', 'integer', 'min:1950', 'max:' . ((int) date('Y') + 1)],
            'car_number' => ['nullable', 'string', 'max:50'],
            'car_color' => ['nullable', 'string', 'max:100'],
            'car_tariff' => ['nullable', Rule::in(array_map(fn (TariffType $tariff) => $tariff->value, TariffType::cases()))],
            'accepts_delivery' => ['nullable', 'boolean'],
        ]);

        $user->forceFill([
            'name' => $data['name'],
            'phone' => preg_replace('/\D+/', '', $data['phone']),
            'email' => $data['email'] ?: null,
            'role' => UserRole::Driver->value,
            'driver_status' => $data['driver_status'] ?? $user->driver_status,
            'balance' => $data['balance'] ?? $user->balance,
            'trust_score' => $data['trust_score'] ?? $user->trust_score,
            'avatar_url' => $data['avatar_url'] ?? $user->avatar_url,
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $user->driverProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $user->driverProfile?->phone ?? preg_replace('/\D+/', '', $data['phone']),
                'email' => $user->driverProfile?->email ?? ($data['email'] ?: null),
                'first_name' => $data['driver_first_name'] ?? $user->driverProfile?->first_name ?? $data['name'],
                'last_name' => $data['driver_last_name'] ?? $user->driverProfile?->last_name ?? '-',
                'car_brand' => $data['car_brand'] ?? null,
                'car_model' => $data['car_model'] ?? null,
                'car_year' => $data['car_year'] ?? null,
                'car_number' => $data['car_number'] ?? null,
                'car_color' => $data['car_color'] ?? null,
                'car_tariff' => $data['car_tariff'] ?? ($user->driverProfile?->car_tariff?->value ?? $user->driverProfile?->car_tariff ?? TariffType::Economy->value),
                'accepts_delivery' => (bool) ($data['accepts_delivery'] ?? ($user->driverProfile?->accepts_delivery ?? true)),
            ]
        );

        return redirect()->route('admin.drivers')->with('success', 'Водитель обновлён.');
    }
}
