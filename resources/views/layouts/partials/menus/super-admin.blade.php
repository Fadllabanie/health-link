{{-- Super Admin Menu --}}
<li class="menu-item {{ request()->is('super-admin/dashboard*') ? 'active' : '' }}">
    <a href="{{ route('super-admin.dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons iconify" data-icon="tabler:layout-dashboard"></i>
        <div>{{ __('app.dashboard') }}</div>
    </a>
</li>

<li class="menu-item {{ request()->is('super-admin/hospitals*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons iconify" data-icon="tabler:building-hospital"></i>
        <div>المستشفيات</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('super-admin.hospitals.index') ? 'active' : '' }}">
            <a href="{{ route('super-admin.hospitals.index') }}" class="menu-link">
                <div>قائمة المستشفيات</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('super-admin.hospitals.create') ? 'active' : '' }}">
            <a href="{{ route('super-admin.hospitals.create') }}" class="menu-link">
                <div>إضافة مستشفى</div>
            </a>
        </li>
    </ul>
</li>

<li class="menu-item {{ request()->is('super-admin/master-data*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons iconify" data-icon="tabler:database"></i>
        <div>البيانات الأساسية</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('super-admin.master-data.countries*') ? 'active' : '' }}">
            <a href="{{ route('super-admin.master-data.countries.index') }}" class="menu-link">
                <div>الدول</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('super-admin.master-data.cities*') ? 'active' : '' }}">
            <a href="{{ route('super-admin.master-data.cities.index') }}" class="menu-link">
                <div>المدن</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('super-admin.master-data.specialties*') ? 'active' : '' }}">
            <a href="{{ route('super-admin.master-data.specialties.index') }}" class="menu-link">
                <div>التخصصات</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('super-admin.master-data.departments*') ? 'active' : '' }}">
            <a href="{{ route('super-admin.master-data.departments.index') }}" class="menu-link">
                <div>الأقسام</div>
            </a>
        </li>
    </ul>
</li>
