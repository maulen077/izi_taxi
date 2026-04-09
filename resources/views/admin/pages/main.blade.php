@extends('admin.layouts.admin')

@section('content')
    <div class="admin-page">
        <div class="page-header">
            <div>
                <h1>Главная</h1>
                <p>Статистика сегодняшнего дня.</p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Заказы сегодня</div>
                <div class="stat-value">{{ $todayOrdersCount ?? $orders ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Доход дня</div>
                <div class="stat-value">{{ number_format((float) ($todayRevenue ?? $revenue ?? 0), 0, '.', ' ') }}</div>
            </div>
            @if(isset($usersCount))
                <div class="stat-card">
                    <div class="stat-label">Пользователи</div>
                    <div class="stat-value">{{ $usersCount }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Водители</div>
                    <div class="stat-value">{{ $driversCount }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Диспетчеры</div>
                    <div class="stat-value">{{ $dispatcherCount }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Открытые заявки</div>
                    <div class="stat-value">{{ $openTicketsCount }}</div>
                </div>
            @endif
        </div>

        <div class="dashboard-grid">
            <div class="card table-card">
                <h2 class="section-title">Последние заказы</h2>
                <div class="table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Номер</th>
                                <th>Маршрут</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRides as $ride)
                                <tr>
                                    <td>#{{ $ride->id }}</td>
                                    <td>{{ $ride->contact_phone ?? '-' }}</td>
                                    <td>{{ $ride->pickup_address }} -> {{ $ride->dropoff_address }}</td>
                                    <td>{{ $ride->status?->label() ?? $ride->status }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="empty-state">Нет данных.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if(isset($recentUsers))
                <div class="card table-card">
                    <h2 class="section-title">Последние пользователи</h2>
                    <div class="table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Имя</th>
                                    <th>Телефон</th>
                                    <th>Роль</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->phone ?? '-' }}</td>
                                        <td>{{ $user->role?->value ?? $user->role }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="empty-state">Нет данных.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
