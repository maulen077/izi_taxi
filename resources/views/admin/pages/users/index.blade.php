@extends('admin.layouts.admin')

@section('content')
    <div class="admin-page">
        <div class="page-header">
            <div>
                <h1>Пользователи</h1>
                <p>Поиск по номеру телефона.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.users') }}" class="card card-filter">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="search">Номер телефона</label>
                    <input id="search" name="search" type="text" value="{{ request('search') }}" placeholder="7771234567">
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Поиск</button>
                <a href="{{ route('admin.users') }}" class="btn btn-outline">Сбросить</a>
            </div>
        </form>

        <div class="card table-card">
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Язык</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>#{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>{{ $user->email ?? '-' }}</td>
                                <td>{{ $user->role?->value ?? $user->role }}</td>
                                <td>{{ strtoupper($user->language ?? '-') }}</td>
                                <td class="actions">
                                    <a href="{{ route('admin.user_detail', $user) }}">Детали</a>
                                    <a href="{{ route('admin.user_edit', $user) }}">Изменить</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">Пользователи не найдены.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
