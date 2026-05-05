@extends('layouts.app')

@section('title', __('medical_records.record_details'))

@section('content')
<div class="row">
    <div class="col-12 mb-4 d-flex align-items-center gap-3">
        <a href="{{ route('doctor.patients.medical-history', $patient) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('medical_records.record_details') }}</h5>
        @php
            $statusBadge = ['draft' => 'warning', 'finalized' => 'success', 'amended' => 'primary'];
        @endphp
        <span class="badge bg-label-{{ $statusBadge[$medicalRecord->status->value] ?? 'secondary' }} fs-6">
            {{ __('medical_records.'.$medicalRecord->status->value) }}
        </span>

        @can('update', $medicalRecord)
            <a href="{{ route('doctor.patients.medical-records.edit', [$patient, $medicalRecord]) }}"
               class="btn btn-sm btn-warning ms-auto">
                {{ __('medical_records.edit_record') }}
            </a>
        @endcan
    </div>

    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('medical_records.record_details') }}</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('medical_records.visit_date') }}</small>
                        {{ $medicalRecord->visit_date->format('Y-m-d H:i') }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('medical_records.visit_type') }}</small>
                        {{ __('medical_records.'.$medicalRecord->visit_type->value) }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('medical_records.doctor') }}</small>
                        {{ $medicalRecord->doctor->user->full_name ?? '—' }}
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('medical_records.diagnosis') }}</small>
                        {{ $medicalRecord->diagnosis }}
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('medical_records.notes') }}</small>
                        {{ $medicalRecord->notes }}
                    </div>
                </div>
            </div>
        </div>

        @if($medicalRecord->attachments->count())
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('medical_records.attachments') }}</h6></div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('app.name') }}</th>
                            <th>{{ __('medical_records.attachment_description') }}</th>
                            <th>{{ __('medical_records.file_size') }}</th>
                            <th>{{ __('medical_records.uploaded_by') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicalRecord->attachments as $attachment)
                        <tr>
                            <td>{{ $attachment->file_name }}</td>
                            <td>{{ $attachment->description ?? '—' }}</td>
                            <td>{{ number_format($attachment->file_size / 1024, 1) }} KB</td>
                            <td>{{ $attachment->uploader->full_name ?? '—' }}</td>
                            <td>
                                <a href="{{ $attachment->file_url }}" target="_blank" class="btn btn-xs btn-outline-primary">
                                    <i class="bx bx-download"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($medicalRecord->prescriptions->count())
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('medical_records.prescriptions') }}</h6></div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('prescriptions.prescription_number') }}</th>
                            <th>{{ __('prescriptions.items_count') }}</th>
                            <th>{{ __('app.status') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicalRecord->prescriptions as $rx)
                        <tr>
                            <td><code>{{ $rx->prescription_number }}</code></td>
                            <td>{{ $rx->items->count() }}</td>
                            <td>
                                <span class="badge bg-label-info">{{ __('prescriptions.'.$rx->status->value) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('doctor.prescriptions.show', $rx) }}" class="btn btn-xs btn-outline-secondary">
                                    {{ __('app.view') }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('medical_records.patient_info') }}</h6></div>
            <div class="card-body">
                <p class="mb-1">
                    <strong>{{ $patient->user->first_name }} {{ $patient->user->last_name }}</strong>
                </p>
                <small class="text-muted d-block">{{ $patient->medical_record_number }}</small>
                @if($patient->blood_type)
                <div class="mt-2">
                    <small class="text-muted d-block">{{ __('patients.blood_type') }}</small>
                    {{ $patient->blood_type->value }}
                </div>
                @endif
                @if($patient->allergies)
                <div class="mt-2">
                    <small class="text-muted d-block">{{ __('patients.allergies') }}</small>
                    {{ $patient->allergies }}
                </div>
                @endif
            </div>
        </div>

        @if($medicalRecord->status !== \App\Enums\RecordStatus::Draft && $medicalRecord->canBeEdited())
        <div class="alert alert-warning small">
            {{ __('medical_records.edit_window_note') }}
        </div>
        @endif
    </div>
</div>
@endsection
