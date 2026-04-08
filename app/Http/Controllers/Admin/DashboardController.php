<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {
    }

    public function index(): View
    {
        $user = Auth::user();
        $stats = $this->dashboardService->stats($user);

        return view('admin.pages.main', array_merge($stats, [
            'currentRole' => $user?->role?->value ?? (string) $user?->role,
        ]));
    }
}
