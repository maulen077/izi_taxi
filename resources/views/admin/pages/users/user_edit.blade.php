@extends('admin.layouts.admin')

@section('content')
    <div class="admin-page">
        <div class="page-header">
            <div>
                <h1>Редактирование пользователя</h1>
                <p>{{ $user->name }} #{{ $user->id }}</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.user_detail', $user) }}" class="btn btn-outline">Детали</a>
                <a href="{{ route('admin.users') }}" class="btn btn-outline">Назад</a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.user_update', $user) }}" class="card form-card">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Имя</label>
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
                    <label for="role">Роль</label>
                    <select id="role" name="role">
                        <option value="passenger" @selected(old('role', $user->role?->value ?? $user->role) === 'passenger')>Passenger</option>
                        <option value="driver" @selected(old('role', $user->role?->value ?? $user->role) === 'driver')>Driver</option>
                        <option value="dispatcher" @selected(old('role', $user->role?->value ?? $user->role) === 'dispatcher')>Dispatcher</option>
                        <option value="admin" @selected(old('role', $user->role?->value ?? $user->role) === 'admin')>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="language">Язык</label>
                    <select id="language" name="language">
                        <option value="ru" @selected(old('language', $user->language) === 'ru')>Русский</option>
                        <option value="en" @selected(old('language', $user->language) === 'en')>English</option>
                        <option value="kk" @selected(old('language', $user->language) === 'kk')>Қазақша</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="driver_status">Статус водителя</label>
                    <select id="driver_status" name="driver_status">
                        @foreach(\App\Enums\DriverStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(old('driver_status', $user->driver_status?->value ?? $user->driver_status) === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="balance">Баланс</label>
                    <input id="balance" name="balance" type="number" step="1" value="{{ old('balance', $user->balance) }}">
                </div>
                <div class="form-group">
                    <label for="trust_score">Trust score</label>
                    <input id="trust_score" name="trust_score" type="number" step="1" min="0" max="100" value="{{ old('trust_score', $user->trust_score) }}">
                </div>
                <div class="form-group">
                    <label for="avatar_url">Avatar URL</label>
                    <input id="avatar_url" name="avatar_url" type="url" value="{{ old('avatar_url', $user->avatar_url) }}">
                </div>
                <div class="form-group">
                    <label for="password">Новый пароль</label>
                    <input id="password" name="password" type="password" placeholder="Оставьте пустым, если не менять">
                </div>
            </div>

            <div class="section-title">Профиль водителя</div>
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
                    <label for="car_brand">Марка авто</label>
                    <input id="car_brand" name="car_brand" type="text" value="{{ old('car_brand', $user->driverProfile?->car_brand) }}">
                </div>
                <div class="form-group">
                    <label for="car_model">Модель авто</label>
                    <input id="car_model" name="car_model" type="text" value="{{ old('car_model', $user->driverProfile?->car_model) }}">
                </div>
                <div class="form-group">
                    <label for="car_year">Год выпуска</label>
                    <input id="car_year" name="car_year" type="number" value="{{ old('car_year', $user->driverProfile?->car_year) }}">
                </div>
                <div class="form-group">
                    <label for="car_number">Госномер</label>
                    <input id="car_number" name="car_number" type="text" value="{{ old('car_number', $user->driverProfile?->car_number) }}">
                </div>
                <div class="form-group">
                    <label for="car_color">Цвет авто</label>
                    <input id="car_color" name="car_color" type="text" value="{{ old('car_color', $user->driverProfile?->car_color) }}">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
@endsection
