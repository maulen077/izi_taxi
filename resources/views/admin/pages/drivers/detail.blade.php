@extends('admin.layouts.admin')

@section('content')
    <div class="admin-page">
        <div class="page-header">
            <div>
                <h1>Карточка водителя</h1>
                <p>{{ $user->name }} #{{ $user->id }}</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.driver_edit', $user) }}" class="btn btn-primary">Редактировать</a>
                <a href="{{ route('admin.drivers') }}" class="btn btn-outline">Назад</a>
            </div>
        </div>

        <div class="card profile-card">
            <div class="profile-grid">
                <div><div class="profile-label">ФИО</div><div class="profile-value">{{ trim(($user->driverProfile?->first_name ?? '') . ' ' . ($user->driverProfile?->last_name ?? '')) ?: $user->name }}</div></div>
                <div><div class="profile-label">Телефон</div><div class="profile-value">{{ $user->phone ?? '-' }}</div></div>
                <div><div class="profile-label">Авто</div><div class="profile-value">{{ trim(($user->driverProfile?->car_brand ?? '') . ' ' . ($user->driverProfile?->car_model ?? '')) ?: '-' }}</div></div>
                <div><div class="profile-label">Номер</div><div class="profile-value">{{ $user->driverProfile?->car_number ?? '-' }}</div></div>
                <div><div class="profile-label">Статус</div><div class="profile-value">{{ $user->driver_status?->value ?? $user->driver_status ?? '-' }}</div></div>
                <div><div class="profile-label">Баланс</div><div class="profile-value">{{ number_format((float) $user->balance, 0, '.', ' ') }}</div></div>
            </div>
        </div>

        <div class="card table-card">
            <h2 class="section-title">Последние поездки</h2>
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Телефон</th>
                            <th>Маршрут</th>
                            <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ridesAsDriver as $ride)
                            <tr>
                                <td>#{{ $ride->id }}</td>
                                <td>{{ $ride->contact_phone ?? '-' }}</td>
                                <td>{{ $ride->pickup_address }} -> {{ $ride->dropoff_address }}</td>
                                <td>{{ $ride->status?->value ?? $ride->status }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty-state">Поездок нет.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
