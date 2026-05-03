<li class="menu-item {{ request()->routeIs('hospital-admin.dashboard') ? 'active' : '' }}">
    <a href="{{ route('hospital-admin.dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div>{{ __('app.dashboard') }}</div>
    </a>
</li>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">{{ __('app.management') }}</span>
</li>

<li class="menu-item {{ request()->routeIs('hospital-admin.doctors*') ? 'active open' : '' }}">
    <a href="{{ route('hospital-admin.doctors.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user-check"></i>
        <div>{{ __('doctors.doctors') }}</div>
    </a>
</li>

<li class="menu-item {{ request()->routeIs('hospital-admin.patients*') ? 'active open' : '' }}">
    <a href="{{ route('hospital-admin.patients.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-group"></i>
        <div>{{ __('patients.patients') }}</div>
    </a>
</li>

<li class="menu-item {{ request()->routeIs('hospital-admin.pharmacies*') ? 'active open' : '' }}">
    <a href="{{ route('hospital-admin.pharmacies.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-plus-medical"></i>
        <div>{{ __('pharmacies.pharmacies') }}</div>
    </a>
</li>
