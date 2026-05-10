@extends('layouts.app')

@section('title', $prescription->prescription_number)

@section('content')
<div class="row g-4">
    {{-- Header --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('hospital-admin.prescriptions.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i>
                    </a>
                    <h5 class="mb-0">{{ __('prescriptions.prescription_details') }}: <code>{{ $prescription->prescription_number }}</code></h5>
                </div>
                @php $statusMap = [
                    'pending' => 'warning',
                    'partially_dispensed' => 'info',
                    'dispensed' => 'success',
                    'cancelled' => 'secondary',
                    'expired' => 'danger',
                ]; @endphp
                <span class="badge bg-label-{{ $statusMap[$prescription->status?->value] ?? 'secondary' }} fs-6">
                    {{ __('prescriptions.' . ($prescription->status?->value ?? 'pending')) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <strong>{{ __('prescriptions.patient') }}:</strong><br>
                        {{ $prescription->patient?->user?->first_name }} {{ $prescription->patient?->user?->last_name }}
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('prescriptions.doctor') }}:</strong><br>
                        {{ $prescription->doctor?->user?->first_name }} {{ $prescription->doctor?->user?->last_name }}
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('prescriptions.pharmacy') }}:</strong><br>
                        {{ $prescription->pharmacy?->name ?? '—' }}
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('prescriptions.issued_at') }}:</strong><br>
                        {{ $prescription->created_at?->format('Y-m-d') }}
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('prescriptions.valid_until') }}:</strong><br>
                        {{ $prescription->valid_until?->format('Y-m-d') ?? '—' }}
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('prescriptions.dispensed_at') }}:</strong><br>
                        {{ $prescription->dispensed_at?->format('Y-m-d H:i') ?? '—' }}
                    </div>
                    @if($prescription->diagnosis_summary)
                        <div class="col-12">
                            <strong>{{ __('prescriptions.diagnosis_summary') }}:</strong><br>
                            {{ $prescription->diagnosis_summary }}
                        </div>
                    @endif
                    @if($prescription->notes)
                        <div class="col-12">
                            <strong>{{ __('prescriptions.notes') }}:</strong><br>
                            {{ $prescription->notes }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Items --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('prescriptions.items') }}</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('prescriptions.medicine') }}</th>
                            <th>{{ __('prescriptions.dosage') }}</th>
                            <th>{{ __('prescriptions.frequency') }}</th>
                            <th>{{ __('prescriptions.duration_days') }}</th>
                            <th>{{ __('prescriptions.quantity') }}</th>
                            <th>{{ __('prescriptions.quantity_dispensed') }}</th>
                            <th>{{ __('prescriptions.route') }}</th>
                            <th>{{ __('prescriptions.instructions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prescription->items as $item)
                            <tr>
                                <td>{{ $item->medicine?->name ?? '—' }}</td>
                                <td>{{ $item->dosage }}</td>
                                <td>{{ $item->frequency }}</td>
                                <td>{{ $item->duration_days }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->quantity_dispensed ?? '—' }}</td>
                                <td>{{ $item->route ?? '—' }}</td>
                                <td>{{ $item->instructions ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($prescription->dispensedBy)
        <div class="col-12">
            <div class="alert alert-success">
                <strong>{{ __('prescriptions.dispensed_by') }}:</strong>
                {{ $prescription->dispensedBy?->first_name }} {{ $prescription->dispensedBy?->last_name }}
                — {{ $prescription->dispensed_at?->format('Y-m-d H:i') }}
            </div>
        </div>
    @endif
</div>
@endsection
