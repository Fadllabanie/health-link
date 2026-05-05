<li class="menu-item {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
    <a href="{{ route('patient.dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div>{{ __('app.dashboard') }}</div>
    </a>
</li>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">{{ __('app.management') }}</span>
</li>

<li class="menu-item {{ request()->routeIs('patient.qr-code*') ? 'active' : '' }}">
    <a href="{{ route('patient.qr-code.show') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-qr-scan"></i>
        <div>{{ __('patients.my_qr_code') }}</div>
    </a>
</li>

<li class="menu-item {{ request()->routeIs('patient.prescriptions*') ? 'active open' : '' }}">
    <a href="#" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-file"></i>
        <div>{{ __('prescriptions.prescriptions') }}</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('patient.prescriptions.latest') ? 'active' : '' }}">
            <a href="{{ route('patient.prescriptions.latest') }}" class="menu-link">
                <div>{{ __('prescriptions.latest_prescription') }}</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('patient.prescriptions.index') ? 'active' : '' }}">
            <a href="{{ route('patient.prescriptions.index') }}" class="menu-link">
                <div>{{ __('prescriptions.all_prescriptions') }}</div>
            </a>
        </li>
    </ul>
</li>

<li class="menu-item {{ request()->routeIs('patient.medical-history*') ? 'active' : '' }}">
    <a href="{{ route('patient.medical-history.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-history"></i>
        <div>{{ __('patients.medical_history') }}</div>
    </a>
</li>
