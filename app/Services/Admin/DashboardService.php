<?php

namespace App\Services\Admin;

use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardService
{
    public function stats(User $actor): array
    {
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $todayRides = Ride::query()->whereBetween('created_at', [$todayStart, $todayEnd]);
        $completedToday = (clone $todayRides)->where('status', 'completed');

        $data = [
            'todayOrdersCount' => (clone $todayRides)->count(),
            'todayRevenue' => (float) $completedToday->sum('price'),
            'completedTodayCount' => $completedToday->count(),
        ];

        if (($actor->role?->value ?? (string) $actor->role) === UserRole::Admin->value) {
            $data['usersCount'] = User::query()->count();
            $data['driversCount'] = User::query()->where('role', UserRole::Driver->value)->count();
            $data['dispatcherCount'] = User::query()->where('role', UserRole::Dispatcher->value)->count();
            $data['openTicketsCount'] = SupportTicket::query()->where('status', 'open')->count();
            $data['recentUsers'] = User::query()->latest()->limit(6)->get();
        }

        $data['recentRides'] = Ride::query()->latest()->limit(8)->get();

        return $data;
    }

    public function todaySummary(): array
    {
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        return [
            'orders' => Ride::query()->whereBetween('created_at', [$todayStart, $todayEnd])->count(),
            'revenue' => (float) Ride::query()
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->where('status', 'completed')
                ->sum('price'),
        ];
    }
}
