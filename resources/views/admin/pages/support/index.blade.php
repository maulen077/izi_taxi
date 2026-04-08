@extends('admin.layouts.admin')

@section('content')
    <div class="admin-page">
        <div class="page-header">
            <div>
                <h1>Техподдержка</h1>
                <p>Заявки пользователей.</p>
            </div>
        </div>

        <div class="card table-card">
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Пользователь</th>
                            <th>Телефон</th>
                            <th>Тема</th>
                            <th>Статус</th>
                            <th>Сообщение</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td>#{{ $ticket->id }}</td>
                                <td>{{ $ticket->user?->name ?? '-' }}</td>
                                <td>{{ $ticket->user?->phone ?? '-' }}</td>
                                <td>{{ $ticket->subject?->value ?? $ticket->subject }}</td>
                                <td>{{ $ticket->status?->value ?? $ticket->status }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($ticket->message, 80) }}</td>
                                <td>{{ $ticket->created_at?->format('d.m.Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">Заявок нет.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
@endsection
