@extends('layouts.app')

@section('title', __('patients.patient_details'))

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('hospital-admin.patients.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-arrow-back"></i>
            </a>
            <h5 class="mb-0">
                {{ $patient->user->first_name }} {{ $patient->user->last_name }}
                <small class="text-muted fs-6 ms-2"><code>{{ $patient->medical_record_number }}</code></small>
            </h5>
        </div>
    </div>

    {{-- Profile + QR --}}
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('patients.personal_info') }}</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('app.email') }}</small>
                        {{ $patient->user->email }}
                    </div>
                    <div class="col-md-6">
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
                        <small class="text-muted d-block">{{ __('app.national_id') }}</small>
                        {{ $patient->user->national_id ?? '—' }}
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('app.city') }}</small>
                        {{ $patient->city?->name ?? '—' }}
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('patients.marital_status') }}</small>
                        {{ $patient->marital_status ? __('patients.' . $patient->marital_status->value) : '—' }}
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('patients.occupation') }}</small>
                        {{ $patient->occupation ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('patients.medical_info') }}</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('patients.blood_type') }}</small>
                        <span class="badge bg-danger-subtle text-danger fs-6">{{ $patient->blood_type?->value ?? '—' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('patients.height_cm') }}</small>
                        {{ $patient->height_cm ? $patient->height_cm . ' cm' : '—' }}
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('patients.weight_kg') }}</small>
                        {{ $patient->weight_kg ? $patient->weight_kg . ' kg' : '—' }}
                    </div>
                    @if($patient->allergies)
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('patients.allergies') }}</small>
                        {{ $patient->allergies }}
                    </div>
                    @endif
                    @if($patient->chronic_conditions)
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('patients.chronic_conditions') }}</small>
                        {{ $patient->chronic_conditions }}
                    </div>
                    @endif
                    @if($patient->current_medications)
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('patients.current_medications') }}</small>
                        {{ $patient->current_medications }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($patient->emergency_contact_name)
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('patients.emergency_contact') }}</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('patients.emergency_contact_name') }}</small>
                        {{ $patient->emergency_contact_name }}
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('patients.emergency_contact_phone') }}</small>
                        {{ $patient->emergency_contact_phone ?? '—' }}
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('patients.emergency_contact_relation') }}</small>
                        {{ $patient->emergency_contact_relation ?? '—' }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($patient->insurance_provider)
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('patients.insurance_info') }}</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('patients.insurance_provider') }}</small>
                        {{ $patient->insurance_provider }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('patients.insurance_policy_number') }}</small>
                        {{ $patient->insurance_policy_number ?? '—' }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- QR Code --}}
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('patients.qr_code') }}</h6></div>
            <div class="card-body text-center">
                @if($patient->qrCode && $patient->qrCode->image_path)
                    <img src="{{ Storage::url($patient->qrCode->image_path) }}"
                         alt="QR Code" class="img-fluid mb-2" style="max-width:200px">
                    <div class="text-muted small">
                        {{ __('app.scans') ?? 'Scans' }}: {{ $patient->qrCode->scan_count }}
                    </div>
                    @if($patient->qrCode->isExpired())
                        <span class="badge bg-danger mt-1">{{ __('app.expired') ?? 'منتهي الصلاحية' }}</span>
                    @endif
                @else
                    <p class="text-muted">{{ __('patients.qr_generated') }}</p>
                @endif
            </div>
        </div>

        {{-- Summary stats --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('patients.medical_records') }}</span>
                    <span class="fw-semibold">{{ $patient->medicalRecords->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('patients.prescriptions') }}</span>
                    <span class="fw-semibold">{{ $patient->prescriptions->count() }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">{{ __('patients.appointments') }}</span>
                    <span class="fw-semibold">{{ $patient->appointments->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
