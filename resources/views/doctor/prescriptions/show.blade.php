@extends('layouts.app')

@section('title', $prescription->prescription_number)

@section('content')
<div class="row">
    <div class="col-12 mb-4 d-flex align-items-center gap-3">
        <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">
            {{ __('prescriptions.prescription') }}: <code>{{ $prescription->prescription_number }}</code>
        </h5>
        @php
            $badgeMap = ['pending' => 'warning', 'dispensed' => 'success', 'partially_dispensed' => 'info', 'cancelled' => 'danger'];
            $badge = $badgeMap[$prescription->status->value] ?? 'secondary';
        @endphp
        <span class="badge bg-label-{{ $badge }} fs-6">{{ __('prescriptions.'.$prescription->status->value) }}</span>

        @if($prescription->status->value === 'pending')
            <a href="{{ route('doctor.prescriptions.edit', $prescription) }}" class="btn btn-sm btn-warning ms-auto">
                {{ __('app.edit') }}
            </a>
            <form method="POST" action="{{ route('doctor.prescriptions.cancel', $prescription) }}" class="d-inline"
                  onsubmit="return confirm('{{ __('prescriptions.confirm_cancel') }}')">
                @csrf
                <input type="hidden" name="reason" id="cancel-reason" value="">
                <button type="button" class="btn btn-sm btn-outline-danger"
                    onclick="document.getElementById('cancel-reason').value = prompt('{{ __('prescriptions.cancel_reason_prompt') }}') || ''; if(document.getElementById('cancel-reason').value) this.closest('form').submit();">
                    {{ __('prescriptions.cancel') }}
                </button>
            </form>
        @endif
    </div>

    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('prescriptions.details') }}</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('patients.patient') }}</small>
                        {{ $prescription->patient->user->first_name }} {{ $prescription->patient->user->last_name }}
                        <small class="text-muted">({{ $prescription->patient->medical_record_number }})</small>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('prescriptions.issued_at') }}</small>
                        {{ $prescription->issued_at?->format('Y-m-d') }}
                    </div>
                    @if($prescription->valid_until)
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('prescriptions.valid_until') }}</small>
                        {{ $prescription->valid_until->format('Y-m-d') }}
                    </div>
                    @endif
                    @if($prescription->diagnosis_summary)
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('prescriptions.diagnosis_summary') }}</small>
                        {{ $prescription->diagnosis_summary }}
                    </div>
                    @endif
                    @if($prescription->notes)
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('prescriptions.notes') }}</small>
                        {{ $prescription->notes }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('prescriptions.items') }}</h6></div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('prescriptions.medicine') }}</th>
                            <th>{{ __('prescriptions.dosage') }}</th>
                            <th>{{ __('prescriptions.frequency') }}</th>
                            <th>{{ __('prescriptions.qty') }}</th>
                            <th>{{ __('prescriptions.dispensed') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prescription->items as $item)
                        <tr>
                            <td>{{ $item->medicine->name }}</td>
                            <td>{{ $item->dosage ?? '—' }}</td>
                            <td>{{ $item->frequency ?? '—' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>
                                @if($item->is_dispensed)
                                    <span class="badge bg-success">{{ $item->quantity_dispensed }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($prescription->dispensed_at)
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('prescriptions.dispense_info') }}</h6></div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted d-block">{{ __('prescriptions.dispensed_at') }}</small>
                    {{ $prescription->dispensed_at->format('Y-m-d H:i') }}
                </div>
                @if($prescription->pharmacy)
                <div>
                    <small class="text-muted d-block">{{ __('prescriptions.pharmacy') }}</small>
                    {{ $prescription->pharmacy->name }}
                </div>
                @endif
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('prescriptions.total_amount') }}</span>
                    <span class="fw-bold">{{ number_format($prescription->total_amount, 2) }} {{ __('app.currency') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
