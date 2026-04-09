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
                                    <a href="{{ route('admin.driver_detail', $driver) }}" title="Открыть карточку водителя" aria-label="Открыть карточку водителя">
                                        <svg viewBox="0 0 24 24" aria-hidden="true" style="width: 18px; height: 18px;"><path d="M12 5c5.23 0 9.27 4.11 10.8 6.03a1.53 1.53 0 0 1 0 1.94C21.27 14.89 17.23 19 12 19S2.73 14.89 1.2 12.97a1.53 1.53 0 0 1 0-1.94C2.73 9.11 6.77 5 12 5zm0 2C7.89 7 4.52 10.14 3.28 12 4.52 13.86 7.89 17 12 17s7.48-3.14 8.72-5C19.48 10.14 16.11 7 12 7zm0 2.5A2.5 2.5 0 1 1 9.5 12 2.5 2.5 0 0 1 12 9.5z" fill="currentColor"/></svg>
                                    </a>
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
