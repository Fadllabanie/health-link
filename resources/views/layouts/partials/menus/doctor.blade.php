<li class="menu-item {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
    <a href="{{ route('doctor.dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div>{{ __('app.dashboard') }}</div>
    </a>
</li>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">{{ __('app.management') }}</span>
</li>

<li class="menu-item {{ request()->routeIs('doctor.prescriptions*') ? 'active open' : '' }}">
    <a href="#" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-file"></i>
        <div>{{ __('prescriptions.prescriptions') }}</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('doctor.prescriptions.index') ? 'active' : '' }}">
            <a href="{{ route('doctor.prescriptions.index') }}" class="menu-link">
                <div>{{ __('prescriptions.prescriptions') }}</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('doctor.prescriptions.create') ? 'active' : '' }}">
            <a href="{{ route('doctor.prescriptions.create') }}" class="menu-link">
                <div>{{ __('prescriptions.add_prescription') }}</div>
            </a>
        </li>
    </ul>
</li>
