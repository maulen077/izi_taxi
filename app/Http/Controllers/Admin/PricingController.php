<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PricingSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function __construct(
        private readonly PricingSettingsService $pricingSettingsService,
    ) {
    }

    public function index(): View
    {
        return view('admin.pages.pricing.index', [
            'rates' => $this->pricingSettingsService->all(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'taxi_rate_per_km' => ['required', 'integer', 'min:0'],
            'delivery_rate_per_km' => ['required', 'integer', 'min:0'],
        ]);

        $this->pricingSettingsService->update($data);

        return redirect()
            ->route('admin.pricing')
            ->with('success', 'Тарифы обновлены.');
    }
}
