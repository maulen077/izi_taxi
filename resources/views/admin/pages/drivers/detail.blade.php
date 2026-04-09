@extends('admin.layouts.admin')

@section('content')
    @php
        $profile = $user->driverProfile;
        $resolveMediaUrl = function (?string $path): ?string {
            if (! $path) {
                return null;
            }

            if (preg_match('/^(https?:)?\/\//i', $path) || str_starts_with($path, 'data:')) {
                return $path;
            }

            return asset(ltrim($path, '/'));
        };
        $isPreviewableImage = function (?string $path): bool {
            if (! $path) {
                return false;
            }

            return str_starts_with($path, 'data:image/')
                || (bool) preg_match('/\.(jpg|jpeg|png|gif|webp|bmp|svg)(\?.*)?$/i', $path);
        };
        $carMedia = [
            'Фото спереди' => $profile?->car_photo_front,
            'Фото сбоку' => $profile?->car_photo_side,
            'Фото салона' => $profile?->car_photo_interior,
        ];
        $documents = [
            'Водительское удостоверение' => $profile?->license_path,
            'Документ личности' => $profile?->id_document_path,
            'Техпаспорт' => $profile?->vehicle_registration_path,
        ];
    @endphp

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
                <div><div class="profile-label">ФИО</div><div class="profile-value">{{ trim(($profile?->first_name ?? '') . ' ' . ($profile?->last_name ?? '')) ?: $user->name }}</div></div>
                <div><div class="profile-label">Имя пользователя</div><div class="profile-value">{{ $user->name }}</div></div>
                <div><div class="profile-label">Телефон</div><div class="profile-value">{{ $user->phone ?? '-' }}</div></div>
                <div><div class="profile-label">Email</div><div class="profile-value">{{ $user->email ?? '-' }}</div></div>
                <div><div class="profile-label">Авто</div><div class="profile-value">{{ trim(($profile?->car_brand ?? '') . ' ' . ($profile?->car_model ?? '')) ?: '-' }}</div></div>
                <div><div class="profile-label">Марка</div><div class="profile-value">{{ $profile?->car_brand ?? '-' }}</div></div>
                <div><div class="profile-label">Модель</div><div class="profile-value">{{ $profile?->car_model ?? '-' }}</div></div>
                <div><div class="profile-label">Год</div><div class="profile-value">{{ $profile?->car_year ?? '-' }}</div></div>
                <div><div class="profile-label">Номер</div><div class="profile-value">{{ $profile?->car_number ?? '-' }}</div></div>
                <div><div class="profile-label">Цвет</div><div class="profile-value">{{ $profile?->car_color ?? '-' }}</div></div>
                <div><div class="profile-label">Тариф машины</div><div class="profile-value">{{ $profile?->car_tariff?->value ?? $profile?->car_tariff ?? '-' }}</div></div>
                <div><div class="profile-label">Доставка</div><div class="profile-value">{{ ($profile?->accepts_delivery ?? true) ? 'Включена' : 'Отключена' }}</div></div>
                <div><div class="profile-label">Статус</div><div class="profile-value">{{ $user->driver_status?->value ?? $user->driver_status ?? '-' }}</div></div>
                <div><div class="profile-label">Рейтинг</div><div class="profile-value">{{ $user->trust_score ?? 0 }}</div></div>
                <div><div class="profile-label">Баланс</div><div class="profile-value">{{ number_format((float) $user->balance, 0, '.', ' ') }}</div></div>
                <div><div class="profile-label">Статус заявки</div><div class="profile-value">{{ $profile?->application_status?->value ?? $profile?->application_status ?? '-' }}</div></div>
                <div><div class="profile-label">Дата подачи</div><div class="profile-value">{{ optional($profile?->submitted_at)->format('d.m.Y H:i') ?? '-' }}</div></div>
                <div><div class="profile-label">Дата одобрения</div><div class="profile-value">{{ optional($profile?->approved_at)->format('d.m.Y H:i') ?? '-' }}</div></div>
                <div><div class="profile-label">Заметки</div><div class="profile-value">{{ $profile?->notes ?? '-' }}</div></div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.driver_capabilities_update', $user) }}" class="card form-card" style="margin-bottom: 24px;">
            @csrf
            @method('PUT')

            <div class="section-title">Настройки выдачи заказов</div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="car_tariff">Тариф машины</label>
                    <select id="car_tariff" name="car_tariff">
                        @foreach($tariffOptions as $tariff)
                            <option value="{{ $tariff->value }}" @selected(old('car_tariff', $profile?->car_tariff?->value ?? $profile?->car_tariff ?? 'economy') === $tariff->value)>
                                {{ ucfirst($tariff->value) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="accepts_delivery">Получать заказы доставки</label>
                    <input type="hidden" name="accepts_delivery" value="0">
                    <select id="accepts_delivery" name="accepts_delivery">
                        <option value="1" @selected((string) old('accepts_delivery', (int) ($profile?->accepts_delivery ?? true)) === '1')>Да</option>
                        <option value="0" @selected((string) old('accepts_delivery', (int) ($profile?->accepts_delivery ?? true)) === '0')>Нет</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Сохранить настройки</button>
            </div>
        </form>

        <div class="card table-card" style="margin-bottom: 24px;">
            <h2 class="section-title">Документы и фотографии</h2>
            <div class="profile-grid" style="margin-top: 20px;">
                @foreach($documents as $label => $path)
                    @php($documentUrl = $resolveMediaUrl($path))
                    <div>
                        <div class="profile-label">{{ $label }}</div>
                        <div class="profile-value">
                            @if($documentUrl)
                                <a href="{{ $documentUrl }}" target="_blank" rel="noopener noreferrer">{{ $path }}</a>
                                @if($isPreviewableImage($path))
                                    <div style="margin-top: 12px;">
                                        <img src="{{ $documentUrl }}" alt="{{ $label }}" style="max-width: 100%; max-height: 220px; border-radius: 14px; border: 1px solid var(--admin-border);">
                                    </div>
                                @endif
                            @else
                                -
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="profile-grid" style="margin-top: 24px;">
                @foreach($carMedia as $label => $path)
                    @php($mediaUrl = $resolveMediaUrl($path))
                    <div>
                        <div class="profile-label">{{ $label }}</div>
                        <div class="profile-value">
                            @if($mediaUrl)
                                <a href="{{ $mediaUrl }}" target="_blank" rel="noopener noreferrer">{{ $path }}</a>
                                @if($isPreviewableImage($path))
                                    <div style="margin-top: 12px;">
                                        <img src="{{ $mediaUrl }}" alt="{{ $label }}" style="max-width: 100%; max-height: 220px; border-radius: 14px; border: 1px solid var(--admin-border);">
                                    </div>
                                @endif
                            @else
                                -
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card table-card">
            <h2 class="section-title">Последние поездки</h2>
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Пассажир</th>
                            <th>Маршрут</th>
                            <th>Режим</th>
                            <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ridesAsDriver as $ride)
                            <tr>
                                <td>#{{ $ride->id }}</td>
                                <td>{{ $ride->passenger?->phone ?? $ride->passenger?->name ?? '-' }}</td>
                                <td>{{ $ride->pickup_address }} -> {{ $ride->destination_address ?? '-' }}</td>
                                <td>{{ $ride->mode?->value ?? $ride->mode }}</td>
                                <td>{{ $ride->status?->value ?? $ride->status }}</td>
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
