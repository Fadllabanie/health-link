@extends('layouts.app')

@section('title', __('patients.patient_details'))

@section('content')
<div class="row">
    <div class="col-12 mb-4 d-flex align-items-center gap-3">
        <a href="{{ route('doctor.patients.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('patients.patient_details') }}</h5>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('doctor.patients.medical-history', $patient) }}" class="btn btn-sm btn-outline-primary">
                <i class="bx bx-file-blank me-1"></i>{{ __('patients.medical_history') }}
            </a>
            <a href="{{ route('doctor.patients.medical-records.create', $patient) }}" class="btn btn-sm btn-outline-success">
                <i class="bx bx-plus me-1"></i>{{ __('medical_records.add_record') }}
            </a>
            <a href="{{ route('doctor.prescriptions.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary">
                <i class="bx bx-plus me-1"></i>{{ __('prescriptions.new_prescription') }}
            </a>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('patients.personal_info') }}</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('app.name') }}</small>
                        <strong>{{ $patient->user->full_name }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('patients.medical_record_number') }}</small>
                        <code>{{ $patient->medical_record_number }}</code>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('app.email') }}</small>
                        {{ $patient->user->email }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('app.phone') }}</small>
                        {{ $patient->user->phone ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('app.gender') }}</small>
                        {{ $patient->user->gender ? __('app.'.$patient->user->gender) : '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('app.date_of_birth') }}</small>
                        {{ $patient->user->date_of_birth?->format('Y-m-d') ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('patients.marital_status') }}</small>
                        {{ $patient->marital_status ? __('patients.'.$patient->marital_status->value) : '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('patients.occupation') }}</small>
                        {{ $patient->occupation ?? '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('patients.medical_info') }}</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('patients.blood_type') }}</small>
                        {{ $patient->blood_type?->value ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('patients.height_cm') }}</small>
                        {{ $patient->height_cm ? $patient->height_cm.' سم' : '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('patients.weight_kg') }}</small>
                        {{ $patient->weight_kg ? $patient->weight_kg.' كجم' : '—' }}
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('patients.allergies') }}</small>
                        {{ $patient->allergies ?? '—' }}
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('patients.chronic_conditions') }}</small>
                        {{ $patient->chronic_conditions ?? '—' }}
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('patients.current_medications') }}</small>
                        {{ $patient->current_medications ?? '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">{{ __('patients.medical_records') }}</h6>
                <a href="{{ route('doctor.patients.medical-history', $patient) }}" class="btn btn-xs btn-outline-secondary">
                    {{ __('app.show_all') }}
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('medical_records.visit_date') }}</th>
                            <th>{{ __('medical_records.visit_type') }}</th>
                            <th>{{ __('app.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patient->medicalRecords as $record)
                            <tr>
                                <td>
                                    <a href="{{ route('doctor.patients.medical-records.show', [$patient, $record]) }}">
                                        {{ $record->visit_date->format('Y-m-d') }}
                                    </a>
                                </td>
                                <td>{{ __('medical_records.'.$record->visit_type->value) }}</td>
                                <td>
                                    @php $badge = ['draft'=>'warning','finalized'=>'success','amended'=>'primary'][$record->status->value] ?? 'secondary' @endphp
                                    <span class="badge bg-label-{{ $badge }}">{{ __('medical_records.'.$record->status->value) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-2">{{ __('patients.no_medical_records') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('patients.emergency_contact') }}</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('patients.emergency_contact_name') }}</small>
                        {{ $patient->emergency_contact_name ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('patients.emergency_contact_phone') }}</small>
                        {{ $patient->emergency_contact_phone ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('patients.emergency_contact_relation') }}</small>
                        {{ $patient->emergency_contact_relation ?? '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
