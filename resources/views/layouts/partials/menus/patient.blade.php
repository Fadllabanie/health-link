<li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <a href="{{ route('dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div>{{ __('app.dashboard') }}</div>
    </a>
</li>
