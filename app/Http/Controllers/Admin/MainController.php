<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\DriverApplication;
use App\Models\Ride;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $usersCount = User::query()->count();
        $passengersCount = User::query()->where('role', UserRole::Passenger->value)->count();
        $driversCount = User::query()->where('role', UserRole::Driver->value)->count();
        $adminCount = User::query()->where('role', UserRole::Admin->value)->count();
        $activeDrivers = User::query()->where('role', UserRole::Driver->value)->where('driver_status', 'online')->count();
        $completedRides = Ride::query()->where('status', RideStatus::Completed->value)->count();
        $searchingRides = Ride::query()->where('status', RideStatus::Searching->value)->count();
        $pendingApplications = DriverApplication::query()->where('status', 'pending')->count();
        $openTickets = SupportTicket::query()->where('status', 'open')->count();

        $recentUsers = User::query()
            ->with('driverProfile')
            ->latest()
            ->limit(10)
            ->get();

        $recentRides = Ride::query()
            ->with(['passenger', 'driver'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.pages.main', compact(
            'usersCount',
            'passengersCount',
            'driversCount',
            'adminCount',
            'activeDrivers',
            'completedRides',
            'searchingRides',
            'pendingApplications',
            'openTickets',
            'recentUsers',
            'recentRides'
        ));
    }
}
