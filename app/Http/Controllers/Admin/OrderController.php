<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService
    ) {
    }

    public function index(Request $request): View
    {
        $filters = [
            'phone' => $request->string('phone')->toString(),
            'date_from' => $request->string('date_from')->toString(),
            'date_to' => $request->string('date_to')->toString(),
        ];

        $orders = $this->orderService->query($filters)->paginate(20)->withQueryString();

        return view('admin.pages.orders.index', [
            'orders' => $orders,
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.pages.orders.index', [
            'orders' => $this->orderService->query()->paginate(20),
            'filters' => [
                'phone' => '',
                'date_from' => '',
                'date_to' => '',
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'contact_phone' => ['required', 'string', 'max:30'],
            'passenger_name' => ['nullable', 'string', 'max:255'],
            'pickup_address' => ['required', 'string', 'max:255'],
            'dropoff_address' => ['required', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->orderService->create($data, Auth::user());

        return redirect()->route(str_starts_with($request->route()?->getName() ?? '', 'dispatcher.') ? 'dispatcher.orders' : 'admin.orders')
            ->with('success', 'Заказ создан.');
    }
}
