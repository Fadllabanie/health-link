@extends('layouts.app')

@section('title', __('medical_records.history'))

@section('content')
<div class="row">
    <div class="col-12 mb-4 d-flex align-items-center gap-3">
        <h5 class="mb-0">{{ __('medical_records.history') }}</h5>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('doctor.patients.medical-records.create', $patient) }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i>{{ __('medical_records.add_record') }}
            </a>
        </div>
    </div>

    <div class="col-12 mb-3">
        <div class="card bg-label-primary">
            <div class="card-body py-3">
                <div class="row g-2">
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('patients.patient') }}</small>
                        <strong>{{ $patient->user->first_name }} {{ $patient->user->last_name }}</strong>
                        <small class="text-muted ms-1">({{ $patient->medical_record_number }})</small>
                    </div>
                    @if($patient->blood_type)
                    <div class="col-md-2">
                        <small class="text-muted d-block">{{ __('patients.blood_type') }}</small>
                        <strong>{{ $patient->blood_type->value }}</strong>
                    </div>
                    @endif
                    @if($patient->allergies)
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('patients.allergies') }}</small>
                        {{ $patient->allergies }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        @forelse($records as $record)
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center gap-3">
                    <div>
                        <i class="bx bx-calendar me-1"></i>
                        {{ $record->visit_date->format('Y-m-d') }}
                        <span class="ms-2 text-muted">{{ $record->visit_date->format('H:i') }}</span>
                    </div>
                    <span class="badge bg-label-info">{{ __('medical_records.'.$record->visit_type->value) }}</span>
                    @php
                        $statusBadge = ['draft' => 'warning', 'finalized' => 'success', 'amended' => 'primary'];
                    @endphp
                    <span class="badge bg-label-{{ $statusBadge[$record->status->value] ?? 'secondary' }}">
                        {{ __('medical_records.'.$record->status->value) }}
                    </span>
                    <small class="text-muted ms-auto">{{ __('medical_records.doctor') }}: {{ $record->doctor->user->full_name ?? '' }}</small>
                    <a href="{{ route('doctor.patients.medical-records.show', [$patient, $record]) }}" class="btn btn-sm btn-outline-secondary">
                        {{ __('app.view') }}
                    </a>
                </div>
                <div class="card-body py-2">
                    <div class="row g-2">
                        @if($record->diagnosis)
                        <div class="col-md-6">
                            <small class="text-muted d-block">{{ __('medical_records.diagnosis') }}</small>
                            {{ Str::limit($record->diagnosis, 150) }}
                        </div>
                        @endif
                        @if($record->notes)
                        <div class="col-md-6">
                            <small class="text-muted d-block">{{ __('medical_records.notes') }}</small>
                            {{ Str::limit($record->notes, 150) }}
                        </div>
                        @endif
                        @if($record->attachments->count())
                        <div class="col-12">
                            <small class="text-muted">
                                <i class="bx bx-paperclip me-1"></i>
                                {{ $record->attachments->count() }} {{ __('medical_records.attachments') }}
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">{{ __('medical_records.no_records') }}</div>
        @endforelse

        {{ $records->links() }}
    </div>
</div>
@endsection
