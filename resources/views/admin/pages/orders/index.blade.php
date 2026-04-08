@extends('admin.layouts.admin')

@section('content')
    <div class="admin-page">
        <div class="page-header">
            <div>
                <h1>Заказы</h1>
                <p>История заказов, фильтр по дате и создание нового заказа.</p>
            </div>
        </div>

        <div class="card form-card">
            <h2 class="section-title">Создать заказ</h2>
            <form method="POST" action="{{ route(str_starts_with(request()->route()?->getName() ?? '', 'dispatcher.') ? 'dispatcher.orders.store' : 'admin.orders.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label for="contact_phone">Номер</label>
                        <input id="contact_phone" name="contact_phone" type="text" placeholder="7771234567" value="{{ old('contact_phone') }}">
                    </div>
                    <div class="form-group">
                        <label for="passenger_name">Имя клиента</label>
                        <input id="passenger_name" name="passenger_name" type="text" value="{{ old('passenger_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="pickup_address">Откуда</label>
                        <input id="pickup_address" name="pickup_address" type="text" value="{{ old('pickup_address') }}">
                    </div>
                    <div class="form-group">
                        <label for="dropoff_address">Куда</label>
                        <input id="dropoff_address" name="dropoff_address" type="text" value="{{ old('dropoff_address') }}">
                    </div>
                    <div class="form-group">
                        <label for="price">Цена</label>
                        <input id="price" name="price" type="number" min="0" step="1" value="{{ old('price') }}">
                    </div>
                    <div class="form-group">
                        <label for="notes">Комментарий</label>
                        <input id="notes" name="notes" type="text" value="{{ old('notes') }}">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Создать заказ</button>
                </div>
            </form>
        </div>

        <form method="GET" action="{{ str_starts_with(request()->route()?->getName() ?? '', 'dispatcher.') ? route('dispatcher.orders') : route('admin.orders') }}" class="card card-filter">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="phone">Поиск по номеру</label>
                    <input id="phone" name="phone" type="text" value="{{ $filters['phone'] ?? request('phone') }}" placeholder="7771234567">
                </div>
                <div class="form-group">
                    <label for="date_from">Дата от</label>
                    <input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] ?? request('date_from') }}">
                </div>
                <div class="form-group">
                    <label for="date_to">Дата до</label>
                    <input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] ?? request('date_to') }}">
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Применить</button>
                <a href="{{ str_starts_with(request()->route()?->getName() ?? '', 'dispatcher.') ? route('dispatcher.orders') : route('admin.orders') }}" class="btn btn-outline">Сбросить</a>
            </div>
        </form>

        <div class="card table-card">
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Номер</th>
                            <th>Клиент</th>
                            <th>Откуда</th>
                            <th>Куда</th>
                            <th>Цена</th>
                            <th>Статус</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->contact_phone ?? '-' }}</td>
                                <td>{{ $order->passenger_name ?? '-' }}</td>
                                <td>{{ $order->pickup_address }}</td>
                                <td>{{ $order->dropoff_address }}</td>
                                <td>{{ number_format((float) $order->price, 0, '.', ' ') }}</td>
                                <td>{{ $order->status?->value ?? $order->status }}</td>
                                <td>{{ $order->created_at?->format('d.m.Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty-state">Заказы не найдены.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
