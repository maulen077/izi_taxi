@php
    $role = auth()->user()?->role?->value ?? (string) auth()->user()?->role;
@endphp

<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <div class="brand-mark">I</div>
        <div>
            <div class="brand-title">Izi Taxi</div>
            <div class="brand-subtitle">{{ $role === 'dispatcher' ? 'Dispatcher panel' : 'Admin panel' }}</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        @if($role === 'dispatcher')
            <a href="{{ route('dispatcher.main') }}" class="sidebar-link {{ request()->routeIs('dispatcher.main') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12l9-8 9 8v8a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z" fill="currentColor"/></svg>
                <span>Главная</span>
            </a>
            <a href="{{ route('dispatcher.orders') }}" class="sidebar-link {{ request()->routeIs('dispatcher.orders*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3h12a2 2 0 0 1 2 2v14H4V5a2 2 0 0 1 2-2zm2 4v2h8V7H8zm0 4v2h8v-2H8z" fill="currentColor"/></svg>
                <span>Заказы</span>
            </a>
            <a href="{{ route('dispatcher.logout') }}" class="sidebar-link">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17v-2h4v-2h-4V11l-4 3 4 3zm8-12H8a2 2 0 0 0-2 2v3h2V7h10v10H8v-3H6v3a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2z" fill="currentColor"/></svg>
                <span>Выход</span>
            </a>
        @else
            <a href="{{ route('admin.main') }}" class="sidebar-link {{ request()->routeIs('admin.main') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12l9-8 9 8v8a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z" fill="currentColor"/></svg>
                <span>Главная</span>
            </a>
            <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm0 2c-4.42 0-8 2.24-8 5v1h16v-1c0-2.76-3.58-5-8-5z" fill="currentColor"/></svg>
                <span>Пользователи</span>
            </a>
            <a href="{{ route('admin.drivers') }}" class="sidebar-link {{ request()->routeIs('admin.drivers*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 17h10l1.5-5H5.5L7 17zm1-8h8l1 3H7l1-3zm2-4h4v2h-4z" fill="currentColor"/></svg>
                <span>Водители</span>
            </a>
            <a href="{{ route('admin.orders') }}" class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3h12a2 2 0 0 1 2 2v14H4V5a2 2 0 0 1 2-2zm2 4v2h8V7H8zm0 4v2h8v-2H8z" fill="currentColor"/></svg>
                <span>Заказы</span>
            </a>
            <a href="{{ route('admin.support') }}" class="sidebar-link {{ request()->routeIs('admin.support*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3a9 9 0 0 0-9 9v4a3 3 0 0 0 3 3h1v-7H5v-1a7 7 0 0 1 14 0v1h-2v7h1a3 3 0 0 0 3-3v-4a9 9 0 0 0-9-9z" fill="currentColor"/></svg>
                <span>Техподдержка</span>
            </a>
            <a href="{{ route('admin.logout') }}" class="sidebar-link">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17v-2h4v-2h-4V11l-4 3 4 3zm8-12H8a2 2 0 0 0-2 2v3h2V7h10v10H8v-3H6v3a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2z" fill="currentColor"/></svg>
                <span>Выход</span>
            </a>
        @endif
    </nav>
</aside>
