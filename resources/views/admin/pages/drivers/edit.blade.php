@extends('admin.layouts.admin')

@section('content')
    <div class="admin-page">
        <div class="page-header">
            <div>
                <h1>Редактирование водителя</h1>
                <p>{{ $user->name }} #{{ $user->id }}</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.driver_detail', $user) }}" class="btn btn-outline">Детали</a>
                <a href="{{ route('admin.drivers') }}" class="btn btn-outline">Назад</a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.driver_update', $user) }}" class="card form-card">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Имя пользователя</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}">
                </div>
                <div class="form-group">
                    <label for="phone">Телефон</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}">
                </div>
                <div class="form-group">
                    <label for="driver_status">Статус</label>
                    <select id="driver_status" name="driver_status">
                        @foreach(['offline','online','order-received','en-route-to-pickup','passenger-waiting','in-progress'] as $status)
                            <option value="{{ $status }}" @selected(old('driver_status', $user->driver_status?->value ?? $user->driver_status) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="balance">Баланс</label>
                    <input id="balance" name="balance" type="number" step="1" value="{{ old('balance', $user->balance) }}">
                </div>
                <div class="form-group">
                    <label for="trust_score">Рейтинг надежности</label>
                    <input id="trust_score" name="trust_score" type="number" step="1" min="10" max="100" value="{{ old('trust_score', $user->trust_score) }}">
                </div>
            </div>

            <div class="section-title">Авто</div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="driver_first_name">Имя</label>
                    <input id="driver_first_name" name="driver_first_name" type="text" value="{{ old('driver_first_name', $user->driverProfile?->first_name) }}">
                </div>
                <div class="form-group">
                    <label for="driver_last_name">Фамилия</label>
                    <input id="driver_last_name" name="driver_last_name" type="text" value="{{ old('driver_last_name', $user->driverProfile?->last_name) }}">
                </div>
                <div class="form-group">
                    <label for="car_brand">Марка</label>
                    <input id="car_brand" name="car_brand" type="text" value="{{ old('car_brand', $user->driverProfile?->car_brand) }}">
                </div>
                <div class="form-group">
                    <label for="car_model">Модель</label>
                    <input id="car_model" name="car_model" type="text" value="{{ old('car_model', $user->driverProfile?->car_model) }}">
                </div>
                <div class="form-group">
                    <label for="car_year">Год</label>
                    <input id="car_year" name="car_year" type="number" value="{{ old('car_year', $user->driverProfile?->car_year) }}">
                </div>
                <div class="form-group">
                    <label for="car_number">Номер</label>
                    <input id="car_number" name="car_number" type="text" value="{{ old('car_number', $user->driverProfile?->car_number) }}">
                </div>
                <div class="form-group">
                    <label for="car_color">Цвет</label>
                    <input id="car_color" name="car_color" type="text" value="{{ old('car_color', $user->driverProfile?->car_color) }}">
                </div>
                <div class="form-group">
                    <label for="car_tariff">Тариф машины</label>
                    <select id="car_tariff" name="car_tariff">
                        @foreach($tariffOptions as $tariff)
                            <option value="{{ $tariff->value }}" @selected(old('car_tariff', $user->driverProfile?->car_tariff?->value ?? $user->driverProfile?->car_tariff ?? 'economy') === $tariff->value)>
                                {{ ucfirst($tariff->value) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="accepts_delivery">Доставка</label>
                    <input type="hidden" name="accepts_delivery" value="0">
                    <select id="accepts_delivery" name="accepts_delivery">
                        <option value="1" @selected((string) old('accepts_delivery', (int) ($user->driverProfile?->accepts_delivery ?? true)) === '1')>Включена</option>
                        <option value="0" @selected((string) old('accepts_delivery', (int) ($user->driverProfile?->accepts_delivery ?? true)) === '0')>Отключена</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
@endsection
