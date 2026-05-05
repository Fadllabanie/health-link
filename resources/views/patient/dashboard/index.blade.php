@extends('layouts.app')

@section('title', __('patients.portal_dashboard'))

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h5 class="mb-0">{{ __('patients.welcome_patient', ['name' => $patient->user->first_name]) }}</h5>
        <small class="text-muted">{{ __('patients.portal_subtitle') }}</small>
    </div>

    {{-- Stats --}}
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bx bx-file fs-1 text-primary mb-2"></i>
                <h4 class="mb-0">{{ $stats['total_prescriptions'] }}</h4>
                <small class="text-muted">{{ __('prescriptions.prescriptions') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bx bx-time fs-1 text-warning mb-2"></i>
                <h4 class="mb-0">{{ $stats['pending_prescriptions'] }}</h4>
                <small class="text-muted">{{ __('prescriptions.pending') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bx bx-folder fs-1 text-info mb-2"></i>
                <h4 class="mb-0">{{ $stats['medical_records'] }}</h4>
                <small class="text-muted">{{ __('patients.medical_records') }}</small>
            </div>
        </div>
    </div>

    {{-- Profile Card --}}
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0">{{ __('patients.personal_info') }}</h6>
                <span class="badge bg-label-primary fs-6">
                    <i class="bx bx-id-card me-1"></i>{{ $patient->medical_record_number }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('app.full_name') }}</small>
                        <strong>{{ $patient->user->first_name }} {{ $patient->user->last_name }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('app.email') }}</small>
                        {{ $patient->user->email }}
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('app.phone') }}</small>
                        {{ $patient->user->phone ?? '—' }}
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('app.date_of_birth') }}</small>
                        {{ $patient->user->date_of_birth?->format('Y-m-d') ?? '—' }}
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('app.gender') }}</small>
                        {{ $patient->user->gender?->value ?? '—' }}
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('app.city') }}</small>
                        {{ $patient->city?->name ?? '—' }}
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('patients.blood_type') }}</small>
                        {{ $patient->blood_type?->value ?? '—' }}
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('patients.marital_status') }}</small>
                        {{ $patient->marital_status ? __('patients.'.$patient->marital_status->value) : '—' }}
                    </div>
                    @if($patient->allergies)
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('patients.allergies') }}</small>
                        <span class="badge bg-label-danger">{{ $patient->allergies }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('patients.quick_links') }}</h6></div>
            <div class="list-group list-group-flush">
                <a href="{{ route('patient.qr-code.show') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                    <i class="bx bx-qr-scan text-primary"></i>
                    {{ __('patients.my_qr_code') }}
                </a>
                <a href="{{ route('patient.prescriptions.latest') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                    <i class="bx bx-file-blank text-success"></i>
                    {{ __('prescriptions.latest_prescription') }}
                </a>
                <a href="{{ route('patient.prescriptions.index') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                    <i class="bx bx-list-ul text-warning"></i>
                    {{ __('prescriptions.all_prescriptions') }}
                </a>
                <a href="{{ route('patient.medical-history.index') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                    <i class="bx bx-history text-info"></i>
                    {{ __('patients.medical_history') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
