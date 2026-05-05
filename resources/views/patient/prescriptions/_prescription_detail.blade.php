@php
    $badgeMap = ['pending' => 'warning', 'dispensed' => 'success', 'partially_dispensed' => 'info', 'cancelled' => 'danger', 'expired' => 'secondary'];
    $badge = $badgeMap[$prescription->status->value] ?? 'secondary';
@endphp

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0">{{ __('prescriptions.details') }}</h6>
                <span class="badge bg-label-{{ $badge }} fs-6">
                    {{ __('prescriptions.'.$prescription->status->value) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('prescriptions.prescription_number') }}</small>
                        <code>{{ $prescription->prescription_number }}</code>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('prescriptions.doctor') }}</small>
                        {{ $prescription->doctor?->user?->first_name }} {{ $prescription->doctor?->user?->last_name }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('prescriptions.hospital') }}</small>
                        {{ $prescription->hospital?->name }}
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
                    @if($prescription->dispensed_at)
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('prescriptions.dispensed_at') }}</small>
                        {{ $prescription->dispensed_at->format('Y-m-d') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h6 class="mb-0">{{ __('prescriptions.items') }}</h6></div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('prescriptions.medicine') }}</th>
                            <th>{{ __('prescriptions.dosage') }}</th>
                            <th>{{ __('prescriptions.frequency') }}</th>
                            <th>{{ __('prescriptions.duration_days') }}</th>
                            <th>{{ __('prescriptions.quantity') }}</th>
                            <th>{{ __('prescriptions.instructions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prescription->items as $item)
                        <tr>
                            <td>{{ $item->medicine?->name }}</td>
                            <td>{{ $item->dosage }}</td>
                            <td>{{ $item->frequency }}</td>
                            <td>{{ $item->duration_days }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->instructions ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
