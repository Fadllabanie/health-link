<li class="menu-item {{ request()->routeIs('pharmacy.dashboard') ? 'active' : '' }}">
    <a href="{{ route('pharmacy.dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div>{{ __('app.dashboard') }}</div>
    </a>
</li>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">{{ __('app.management') }}</span>
</li>

<li class="menu-item {{ request()->routeIs('pharmacy.prescriptions*') ? 'active open' : '' }}">
    <a href="{{ route('pharmacy.prescriptions.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-file-medical"></i>
        <div>{{ __('prescriptions.prescriptions') }}</div>
    </a>
</li>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">{{ __('pharmacies.inventory') }}</span>
</li>

<li class="menu-item {{ request()->routeIs('pharmacy.inventory.index') || request()->routeIs('pharmacy.inventory.show') || request()->routeIs('pharmacy.inventory.edit') ? 'active open' : '' }}">
    <a href="{{ route('pharmacy.inventory.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-list-ul"></i>
        <div>{{ __('pharmacies.inventory_items') }}</div>
    </a>
</li>

<li class="menu-item {{ request()->routeIs('pharmacy.inventory.expiring') ? 'active open' : '' }}">
    <a href="{{ route('pharmacy.inventory.expiring') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-time-five"></i>
        <div>{{ __('pharmacies.expiry_report') }}</div>
    </a>
</li>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">{{ __('pharmacies.reports') }}</span>
</li>

<li class="menu-item {{ request()->routeIs('pharmacy.reports.stock') ? 'active open' : '' }}">
    <a href="{{ route('pharmacy.reports.stock') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-bar-chart"></i>
        <div>{{ __('pharmacies.stock_report') }}</div>
    </a>
</li>

<li class="menu-item {{ request()->routeIs('pharmacy.reports.movements') ? 'active open' : '' }}">
    <a href="{{ route('pharmacy.reports.movements') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-transfer"></i>
        <div>{{ __('pharmacies.movement_report') }}</div>
    </a>
</li>

<li class="menu-item {{ request()->routeIs('pharmacy.reports.low-stock') ? 'active open' : '' }}">
    <a href="{{ route('pharmacy.reports.low-stock') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-error-alt"></i>
        <div>{{ __('pharmacies.low_stock_report') }}</div>
    </a>
</li>
