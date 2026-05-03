@extends('layouts.app')

@section('title', $prescription->prescription_number)

@section('content')
<div class="row">
    <div class="col-12 mb-4 d-flex align-items-center gap-3">
        <a href="{{ route('pharmacy.prescriptions.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('prescriptions.prescription') }}: <code>{{ $prescription->prescription_number }}</code></h5>
        @php
            $badgeMap = ['pending' => 'warning', 'dispensed' => 'success', 'partially_dispensed' => 'info', 'cancelled' => 'danger'];
            $badge = $badgeMap[$prescription->status->value] ?? 'secondary';
        @endphp
        <span class="badge bg-label-{{ $badge }} fs-6">{{ __('prescriptions.'.$prescription->status->value) }}</span>
    </div>

    <div class="col-md-8">
        {{-- Patient & Doctor info --}}
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
                        <small class="text-muted d-block">{{ __('prescriptions.doctor') }}</small>
                        {{ $prescription->doctor->user->first_name }} {{ $prescription->doctor->user->last_name }}
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('prescriptions.issued_at') }}</small>
                        {{ $prescription->issued_at?->format('Y-m-d') }}
                    </div>
                    @if($prescription->valid_until)
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('prescriptions.valid_until') }}</small>
                        <span class="{{ $prescription->valid_until->isPast() ? 'text-danger' : '' }}">
                            {{ $prescription->valid_until->format('Y-m-d') }}
                        </span>
                    </div>
                    @endif
                    @if($prescription->diagnosis_summary)
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('prescriptions.diagnosis_summary') }}</small>
                        {{ $prescription->diagnosis_summary }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Dispense form --}}
        @if(in_array($prescription->status->value, ['pending', 'partially_dispensed']))
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('prescriptions.dispense') }}</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('pharmacy.prescriptions.dispense', $prescription) }}">
                    @csrf

                    @if($errors->has('error'))
                        <div class="alert alert-danger">{{ $errors->first('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('prescriptions.medicine') }}</th>
                                    <th>{{ __('prescriptions.dosage') }}</th>
                                    <th>{{ __('prescriptions.ordered_qty') }}</th>
                                    <th>{{ __('prescriptions.dispense_qty') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prescription->items as $i => $item)
                                @if(!$item->is_dispensed)
                                <tr>
                                    <td>{{ $item->medicine->name }}</td>
                                    <td>{{ $item->dosage ?? '—' }} {{ $item->frequency ? '/ '.$item->frequency : '' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>
                                        <input type="hidden" name="items[{{ $i }}][item_id]" value="{{ $item->id }}">
                                        <input type="number" name="items[{{ $i }}][quantity]"
                                            class="form-control form-control-sm" style="width:100px"
                                            value="{{ $item->quantity }}" min="0" max="{{ $item->quantity }}">
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="submit" class="btn btn-success">{{ __('prescriptions.dispense') }}</button>
                </form>

                <hr>

                <form method="POST" action="{{ route('pharmacy.prescriptions.reject', $prescription) }}">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label text-danger">{{ __('prescriptions.reject_reason') }}</label>
                            <input type="text" name="reason" class="form-control @error('reason') is-invalid @enderror"
                                placeholder="{{ __('prescriptions.reject_reason_placeholder') }}" required>
                            @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-outline-danger"
                                onclick="return confirm('{{ __('prescriptions.confirm_reject') }}')">
                                {{ __('prescriptions.reject') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @else
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('prescriptions.items') }}</h6></div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('prescriptions.medicine') }}</th>
                            <th>{{ __('prescriptions.dosage') }}</th>
                            <th>{{ __('prescriptions.qty') }}</th>
                            <th>{{ __('prescriptions.dispensed') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prescription->items as $item)
                        <tr>
                            <td>{{ $item->medicine->name }}</td>
                            <td>{{ $item->dosage ?? '—' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->quantity_dispensed ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('prescriptions.total_amount') }}</span>
                    <span class="fw-bold">{{ number_format($prescription->total_amount, 2) }} {{ __('app.currency') }}</span>
                </div>
                @if($prescription->dispensed_at)
                <div class="d-flex justify-content-between">
                    <span class="text-muted">{{ __('prescriptions.dispensed_at') }}</span>
                    <span>{{ $prescription->dispensed_at->format('Y-m-d H:i') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
