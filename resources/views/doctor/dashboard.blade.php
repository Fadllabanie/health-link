@extends('layouts.app')

@section('title', __('app.dashboard'))

@section('content')
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="fw-medium d-block mb-1">{{ __('prescriptions.prescriptions') }}</span>
                        <h3 class="card-title mb-2">{{ $stats['total_prescriptions'] }}</h3>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary">
                            <span class="iconify" data-icon="tabler:file-text"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="fw-medium d-block mb-1">{{ __('prescriptions.pending') }}</span>
                        <h3 class="card-title mb-2">{{ $stats['pending'] }}</h3>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-warning">
                            <span class="iconify" data-icon="tabler:clock"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="fw-medium d-block mb-1">{{ __('prescriptions.dispensed') }}</span>
                        <h3 class="card-title mb-2">{{ $stats['dispensed'] }}</h3>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-success">
                            <span class="iconify" data-icon="tabler:circle-check"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="fw-medium d-block mb-1">{{ __('prescriptions.cancelled') }}</span>
                        <h3 class="card-title mb-2">
                            {{ ($stats['total_prescriptions'] - $stats['pending'] - $stats['dispensed']) > 0
                                ? ($stats['total_prescriptions'] - $stats['pending'] - $stats['dispensed'])
                                : 0 }}
                        </h3>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-danger">
                            <span class="iconify" data-icon="tabler:circle-x"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('prescriptions.prescriptions') }}</h5>
                <a href="{{ route('doctor.prescriptions.create') }}" class="btn btn-primary btn-sm">
                    <span class="iconify" data-icon="tabler:plus"></span>
                    {{ __('prescriptions.add_prescription') }}
                </a>
            </div>
            <div class="card-body">
                <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-outline-primary">
                    {{ __('prescriptions.prescriptions') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
