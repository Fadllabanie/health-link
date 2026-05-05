@extends('layouts.app')

@section('title', __('patients.medical_history'))

@section('content')
<div class="row">
    <div class="col-12 mb-4 d-flex align-items-center gap-3">
        <a href="{{ route('patient.dashboard') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('patients.medical_history') }}</h5>
    </div>

    <div class="col-12">
        @forelse($records as $record)
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bx bx-calendar text-primary"></i>
                    <strong>{{ $record->visit_date?->format('Y-m-d') }}</strong>
                    <span class="badge bg-label-secondary">{{ $record->visit_type?->value }}</span>
                    <span class="badge bg-label-{{ $record->status?->value === 'finalized' ? 'success' : 'warning' }}">
                        {{ $record->status?->value }}
                    </span>
                </div>
                <small class="text-muted">
                    {{ $record->hospital?->name }}
                </small>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('prescriptions.doctor') }}</small>
                        {{ $record->doctor?->user?->first_name }} {{ $record->doctor?->user?->last_name }}
                    </div>
                    @if($record->notes)
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('prescriptions.notes') }}</small>
                        {{ $record->notes }}
                    </div>
                    @endif

                    @if($record->attachments->count() > 0)
                    <div class="col-12">
                        <small class="text-muted d-block mb-1">{{ __('patients.attachments') }}</small>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($record->attachments as $attachment)
                            <a
                                href="{{ asset('storage/'.$attachment->file_path) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-secondary"
                            >
                                <i class="bx bx-paperclip me-1"></i>
                                {{ $attachment->file_name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="alert alert-info">{{ __('patients.no_medical_records') }}</div>
        @endforelse

        @if($records->hasPages())
            {{ $records->links() }}
        @endif
    </div>
</div>
@endsection
