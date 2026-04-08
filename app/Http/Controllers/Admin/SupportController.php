<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SupportService;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function __construct(
        private readonly SupportService $supportService
    ) {
    }

    public function index(): View
    {
        $tickets = $this->supportService->query()->paginate(20);

        return view('admin.pages.support.index', compact('tickets'));
    }
}
