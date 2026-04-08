@extends('admin.layouts.admin')

@section('content')
    <div class="admin-page">
        <div class="page-header">
            <div>
                <h1>Карточка пользователя</h1>
                <p>{{ $user->name }} #{{ $user->id }}</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.user_edit', $user) }}" class="btn btn-primary">Редактировать</a>
                <a href="{{ route('admin.users') }}" class="btn btn-outline">Назад</a>
            </div>
        </div>

        <div class="card profile-card">
            <div class="profile-grid">
                <div>
                    <div class="profile-label">Имя</div>
                    <div class="profile-value">{{ $user->name }}</div>
                </div>
                <div>
                    <div class="profile-label">Телефон</div>
                    <div class="profile-value">{{ $user->phone ?? '-' }}</div>
                </div>
                <div>
                    <div class="profile-label">Email</div>
                    <div class="profile-value">{{ $user->email ?? '-' }}</div>
                </div>
                <div>
                    <div class="profile-label">Роль</div>
                    <div class="profile-value">{{ $user->role?->value ?? $user->role }}</div>
                </div>
                <div>
                    <div class="profile-label">Язык</div>
                    <div class="profile-value">{{ strtoupper($user->language ?? '-') }}</div>
                </div>
                <div>
                    <div class="profile-label">Статус водителя</div>
                    <div class="profile-value">{{ $user->driver_status?->value ?? $user->driver_status ?? '-' }}</div>
                </div>
                <div>
                    <div class="profile-label">Баланс</div>
                    <div class="profile-value">{{ number_format((float) $user->balance, 0, '.', ' ') }}</div>
                </div>
                <div>
                    <div class="profile-label">Trust score</div>
                    <div class="profile-value">{{ number_format((float) $user->trust_score, 0, '.', ' ') }}</div>
                </div>
            </div>
        </div>

        @if($user->driverProfile)
            <div class="card profile-card">
                <h2 class="section-title">Профиль водителя</h2>
                <div class="profile-grid">
                    <div><div class="profile-label">Имя</div><div class="profile-value">{{ $user->driverProfile->first_name ?? '-' }}</div></div>
                    <div><div class="profile-label">Фамилия</div><div class="profile-value">{{ $user->driverProfile->last_name ?? '-' }}</div></div>
                    <div><div class="profile-label">Марка</div><div class="profile-value">{{ $user->driverProfile->car_brand ?? '-' }}</div></div>
                    <div><div class="profile-label">Модель</div><div class="profile-value">{{ $user->driverProfile->car_model ?? '-' }}</div></div>
                    <div><div class="profile-label">Год</div><div class="profile-value">{{ $user->driverProfile->car_year ?? '-' }}</div></div>
                    <div><div class="profile-label">Номер</div><div class="profile-value">{{ $user->driverProfile->car_number ?? '-' }}</div></div>
                    <div><div class="profile-label">Цвет</div><div class="profile-value">{{ $user->driverProfile->car_color ?? '-' }}</div></div>
                </div>
            </div>
        @endif

        <div class="card table-card">
            <h2 class="section-title">Последние поездки как пассажир</h2>
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Статус</th>
                            <th>Маршрут</th>
                            <th>Цена</th>
                            <th>Создана</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ridesAsPassenger as $ride)
                            <tr>
                                <td>#{{ $ride->id }}</td>
                                <td>{{ $ride->status?->value ?? $ride->status }}</td>
                                <td>{{ $ride->pickup_address }} -> {{ $ride->dropoff_address }}</td>
                                <td>{{ number_format((float) $ride->price, 0, '.', ' ') }}</td>
                                <td>{{ $ride->created_at?->format('d.m.Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="empty-state">Поездок нет.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card table-card">
            <h2 class="section-title">Последние поездки как водитель</h2>
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Статус</th>
                            <th>Маршрут</th>
                            <th>Цена</th>
                            <th>Создана</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ridesAsDriver as $ride)
                            <tr>
                                <td>#{{ $ride->id }}</td>
                                <td>{{ $ride->status?->value ?? $ride->status }}</td>
                                <td>{{ $ride->pickup_address }} -> {{ $ride->dropoff_address }}</td>
                                <td>{{ number_format((float) $ride->price, 0, '.', ' ') }}</td>
                                <td>{{ $ride->created_at?->format('d.m.Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="empty-state">Поездок нет.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
