@extends('admin.layouts.admin')

@section('content')
    <div class="admin-page">
        <div class="page-header">
            <div>
                <h1>Тарифы</h1>
                <p>Настройка стоимости за километр для такси и доставки.</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.main') }}" class="btn btn-outline">Назад</a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.pricing.update') }}" class="card form-card">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="taxi_rate_per_km">Такси, тг за 1 км</label>
                    <input
                        id="taxi_rate_per_km"
                        name="taxi_rate_per_km"
                        type="number"
                        min="0"
                        step="1"
                        value="{{ old('taxi_rate_per_km', $rates['taxi_rate_per_km']) }}"
                    >
                    <small>Ставка добавляется к базовой цене выбранного тарифа такси.</small>
                </div>

                <div class="form-group">
                    <label for="delivery_rate_per_km">Доставка, тг за 1 км</label>
                    <input
                        id="delivery_rate_per_km"
                        name="delivery_rate_per_km"
                        type="number"
                        min="0"
                        step="1"
                        value="{{ old('delivery_rate_per_km', $rates['delivery_rate_per_km']) }}"
                    >
                    <small>Стоимость доставки считается полностью по расстоянию.</small>
                </div>
            </div>

            <div class="card" style="margin-top: 24px; background: var(--admin-surface-alt); border: 1px solid var(--admin-border);">
                <div style="padding: 20px 24px;">
                    <div class="section-title" style="margin-bottom: 12px;">Как сейчас считается цена</div>
                    <p style="margin: 0; color: var(--admin-text-muted);">
                        Такси: базовый тариф + расстояние x ставка за км. Доставка: расстояние x ставка за км.
                    </p>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
@endsection
