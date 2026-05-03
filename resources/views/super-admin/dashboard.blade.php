@extends('layouts.app')

@section('title', __('app.dashboard'))

@section('content')
{{-- Stats Cards --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-nowrap text-muted mb-1">{{ __('hospitals.hospitals') }}</span>
                        <h3 class="card-title mb-0">{{ $stats['total_hospitals'] }}</h3>
                    </div>
                    <span class="badge bg-label-primary rounded p-2">
                        <i class="bx bx-plus-medical fs-4"></i>
                    </span>
                </div>
                <div class="mt-2">
                    <small class="text-success me-2">{{ $stats['active_hospitals'] }} {{ __('app.active') }}</small>
                    <small class="text-danger">{{ $stats['suspended_hospitals'] }} {{ __('app.suspended') }}</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-nowrap text-muted mb-1">{{ __('app.active') }}</span>
                        <h3 class="card-title mb-0">{{ $stats['active_hospitals'] }}</h3>
                    </div>
                    <span class="badge bg-label-success rounded p-2">
                        <i class="bx bx-check-circle fs-4"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-nowrap text-muted mb-1">{{ __('app.suspended') }}</span>
                        <h3 class="card-title mb-0">{{ $stats['suspended_hospitals'] }}</h3>
                    </div>
                    <span class="badge bg-label-danger rounded p-2">
                        <i class="bx bx-block fs-4"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="d-block text-nowrap text-muted mb-1">{{ __('app.users') }}</span>
                        <h3 class="card-title mb-0">{{ $stats['total_users'] }}</h3>
                    </div>
                    <span class="badge bg-label-info rounded p-2">
                        <i class="bx bx-group fs-4"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Links --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <a href="{{ route('super-admin.hospitals.create') }}" class="card card-hover text-decoration-none">
            <div class="card-body text-center py-4">
                <i class="bx bx-building-house fs-1 text-primary"></i>
                <p class="mb-0 mt-2 fw-semibold">{{ __('hospitals.add_hospital') }}</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('super-admin.hospitals.index') }}" class="card card-hover text-decoration-none">
            <div class="card-body text-center py-4">
                <i class="bx bx-list-ul fs-1 text-success"></i>
                <p class="mb-0 mt-2 fw-semibold">{{ __('hospitals.hospitals') }}</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('super-admin.master-data.countries.index') }}" class="card card-hover text-decoration-none">
            <div class="card-body text-center py-4">
                <i class="bx bx-data fs-1 text-warning"></i>
                <p class="mb-0 mt-2 fw-semibold">{{ __('app.master_data') }}</p>
            </div>
        </a>
    </div>
</div>

{{-- Recent Activity --}}
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ __('app.recent_activity') }}</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{{ __('app.actions') }}</th>
                    <th>{{ __('app.user') }}</th>
                    <th>{{ __('app.date') }}</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentActivity as $log)
                    <tr>
                        <td><code>{{ $log->action }}</code></td>
                        <td>{{ $log->user?->first_name }} {{ $log->user?->last_name }}</td>
                        <td>{{ $log->created_at?->diffForHumans() }}</td>
                        <td>{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">—</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
