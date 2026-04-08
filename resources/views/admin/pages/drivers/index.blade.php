@extends('admin.layouts.admin')

@section('content')
    <div class="admin-page">
        <div class="page-header">
            <div>
                <h1>Водители</h1>
                <p>Поиск по ФИО и номеру.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.drivers') }}" class="card card-filter">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="search">Поиск</label>
                    <input id="search" name="search" type="text" value="{{ request('search') }}" placeholder="ФИО или номер">
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Поиск</button>
                <a href="{{ route('admin.drivers') }}" class="btn btn-outline">Сбросить</a>
            </div>
        </form>

        <div class="card table-card">
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ФИО</th>
                            <th>Телефон</th>
                            <th>Авто</th>
                            <th>Номер</th>
                            <th>Статус</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $driver)
                            <tr>
                                <td>#{{ $driver->id }}</td>
                                <td>{{ trim(($driver->driverProfile?->first_name ?? '') . ' ' . ($driver->driverProfile?->last_name ?? '')) ?: $driver->name }}</td>
                                <td>{{ $driver->phone ?? '-' }}</td>
                                <td>{{ trim(($driver->driverProfile?->car_brand ?? '') . ' ' . ($driver->driverProfile?->car_model ?? '')) ?: '-' }}</td>
                                <td>{{ $driver->driverProfile?->car_number ?? '-' }}</td>
                                <td>{{ $driver->driver_status?->value ?? $driver->driver_status ?? '-' }}</td>
                                <td class="actions">
                                    <a href="{{ route('admin.driver_detail', $driver) }}">Детали</a>
                                    <a href="{{ route('admin.driver_edit', $driver) }}">Изменить</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">Водители не найдены.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">
                {{ $drivers->links() }}
            </div>
        </div>
    </div>
@endsection
