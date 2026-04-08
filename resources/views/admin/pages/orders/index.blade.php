@extends('admin.layouts.admin')

@section('content')
    @php
        $orderRoutePrefix = str_starts_with(request()->route()?->getName() ?? '', 'dispatcher.') ? 'dispatcher' : 'admin';
        $shouldOpenCreateModal =
            $errors->has('contact_phone') ||
            $errors->has('passenger_name') ||
            $errors->has('pickup_address') ||
            $errors->has('dropoff_address') ||
            $errors->has('price') ||
            $errors->has('notes');
    @endphp

    <div class="admin-page">
        <div class="page-header">
            <div>
                <h1>Заказы</h1>
                <p>История заказов и быстрое создание новой заявки через модальное окно.</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createOrderModal">
                    Создать заказ
                </button>
            </div>
        </div>

        <form method="GET" action="{{ route($orderRoutePrefix . '.orders') }}" class="card card-filter">
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
                <a href="{{ route($orderRoutePrefix . '.orders') }}" class="btn btn-outline">Сбросить</a>
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

    <div class="modal fade admin-modal" id="createOrderModal" tabindex="-1" role="dialog" aria-labelledby="createOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h2 class="modal-title" id="createOrderModalLabel">Создать заказ</h2>
                        <p class="modal-subtitle">Заполните данные клиента и маршрут для новой заявки.</p>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route($orderRoutePrefix . '.orders.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="modal_contact_phone">Номер</label>
                                <input id="modal_contact_phone" name="contact_phone" type="text" placeholder="7771234567" value="{{ old('contact_phone') }}">
                            </div>
                            <div class="form-group">
                                <label for="modal_passenger_name">Имя клиента</label>
                                <input id="modal_passenger_name" name="passenger_name" type="text" value="{{ old('passenger_name') }}">
                            </div>
                            <div class="form-group">
                                <label for="modal_pickup_address">Откуда</label>
                                <input id="modal_pickup_address" name="pickup_address" type="text" value="{{ old('pickup_address') }}">
                            </div>
                            <div class="form-group">
                                <label for="modal_dropoff_address">Куда</label>
                                <input id="modal_dropoff_address" name="dropoff_address" type="text" value="{{ old('dropoff_address') }}">
                            </div>
                            <div class="form-group">
                                <label for="modal_price">Цена</label>
                                <input id="modal_price" name="price" type="number" min="0" step="1" value="{{ old('price') }}">
                            </div>
                            <div class="form-group">
                                <label for="modal_notes">Комментарий</label>
                                <input id="modal_notes" name="notes" type="text" value="{{ old('notes') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Создать заказ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js_bottom')
    @if($shouldOpenCreateModal)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const createOrderModalElement = document.getElementById('createOrderModal');

                if (!createOrderModalElement) {
                    return;
                }

                if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
                    window.jQuery(createOrderModalElement).modal('show');
                    return;
                }

                if (typeof bootstrap !== 'undefined' && typeof bootstrap.Modal === 'function') {
                    const createOrderModal = new bootstrap.Modal(createOrderModalElement);
                    createOrderModal.show();
                }
            });
        </script>
    @endif
@endsection
