@extends('layouts.app')

@section('title', __('app.dashboard'))

@section('content')
<div class="row g-4">
    {{-- Stats Cards --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted mb-1">{{ __('pharmacies.inventory_items') }}</span>
                        <h3 class="mb-0">{{ $totalItems }}</h3>
                    </div>
                    <span class="badge bg-label-primary rounded p-2">
                        <span class="iconify fs-4" data-icon="tabler:medicine-syrup"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted mb-1">{{ __('pharmacies.status_low_stock') }}</span>
                        <h3 class="mb-0 text-warning">{{ $lowStockCount }}</h3>
                    </div>
                    <span class="badge bg-label-warning rounded p-2">
                        <span class="iconify fs-4" data-icon="tabler:alert-triangle"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted mb-1">{{ __('pharmacies.status_expired') }}</span>
                        <h3 class="mb-0 text-danger">{{ $expiredCount }}</h3>
                    </div>
                    <span class="badge bg-label-danger rounded p-2">
                        <span class="iconify fs-4" data-icon="tabler:circle-x"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-muted mb-1">{{ __('pharmacies.expiring_within_30') }}</span>
                        <h3 class="mb-0 text-info">{{ $expiringSoonCount }}</h3>
                    </div>
                    <span class="badge bg-label-info rounded p-2">
                        <span class="iconify fs-4" data-icon="tabler:clock-hour-4"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $pharmacy->name }}</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('pharmacy.inventory.index') }}" class="btn btn-outline-primary">
                        <span class="iconify" data-icon="tabler:list"></span>
                        {{ __('pharmacies.inventory_items') }}
                    </a>
                    <a href="{{ route('pharmacy.inventory.create') }}" class="btn btn-outline-success">
                        <span class="iconify" data-icon="tabler:plus"></span>
                        {{ __('pharmacies.add_inventory') }}
                    </a>
                    <a href="{{ route('pharmacy.inventory.expiring') }}" class="btn btn-outline-warning">
                        <span class="iconify" data-icon="tabler:clock-exclamation"></span>
                        {{ __('pharmacies.expiry_report') }}
                    </a>
                    <a href="{{ route('pharmacy.reports.low-stock') }}" class="btn btn-outline-danger">
                        <span class="iconify" data-icon="tabler:alert-triangle"></span>
                        {{ __('pharmacies.low_stock_report') }}
                    </a>
                    <a href="{{ route('pharmacy.reports.movements') }}" class="btn btn-outline-secondary">
                        <span class="iconify" data-icon="tabler:arrows-exchange"></span>
                        {{ __('pharmacies.stock_movements') }}
                    </a>
                    <a href="{{ route('pharmacy.prescriptions.index') }}" class="btn btn-outline-info">
                        <span class="iconify" data-icon="tabler:prescription"></span>
                        {{ __('prescriptions.prescriptions') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
