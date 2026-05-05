<!doctype html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ __('patients.qr_scan_result') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <style>
        body { font-family: 'Cairo', sans-serif; background: #f5f5f9; }
        .qr-card { max-width: 600px; margin: 40px auto; }
    </style>
</head>
<body>
<div class="container qr-card">
    <div class="card">
        <div class="card-header text-center py-4">
            <h5 class="mb-0">
                <i class="bx bx-qr-scan me-2"></i>{{ __('patients.qr_scan_result') }}
            </h5>
        </div>
        <div class="card-body">
            {{-- Patient Basic Info --}}
            <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded">
                <i class="bx bx-user-circle fs-1 text-primary"></i>
                <div>
                    <h6 class="mb-0">{{ $patient->user->first_name }} {{ $patient->user->last_name }}</h6>
                    <small class="text-muted">
                        {{ __('patients.medical_record_number') }}: <code>{{ $patient->medical_record_number }}</code>
                    </small>
                    @if($patient->primaryHospital)
                    <br><small class="text-muted">{{ $patient->primaryHospital->name }}</small>
                    @endif
                </div>
            </div>

            {{-- Latest Prescription --}}
            @if($latestPrescription)
            <h6 class="mb-3">{{ __('prescriptions.latest_prescription') }}</h6>
            @php
                $badgeMap = ['pending' => 'warning', 'dispensed' => 'success', 'partially_dispensed' => 'info', 'cancelled' => 'danger'];
                $badge = $badgeMap[$latestPrescription->status->value] ?? 'secondary';
            @endphp
            <div class="card border mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <code>{{ $latestPrescription->prescription_number }}</code>
                    <span class="badge bg-label-{{ $badge }}">{{ __('prescriptions.'.$latestPrescription->status->value) }}</span>
                </div>
                <div class="card-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('prescriptions.doctor') }}</small>
                            {{ $latestPrescription->doctor?->user?->first_name }} {{ $latestPrescription->doctor?->user?->last_name }}
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('prescriptions.issued_at') }}</small>
                            {{ $latestPrescription->issued_at?->format('Y-m-d') }}
                        </div>
                        @if($latestPrescription->diagnosis_summary)
                        <div class="col-12">
                            <small class="text-muted d-block">{{ __('prescriptions.diagnosis_summary') }}</small>
                            {{ $latestPrescription->diagnosis_summary }}
                        </div>
                        @endif
                    </div>

                    <h6 class="small text-muted mb-2">{{ __('prescriptions.items') }}</h6>
                    <ul class="list-unstyled mb-0">
                        @foreach($latestPrescription->items as $item)
                        <li class="d-flex align-items-center gap-2 mb-1">
                            <i class="bx bx-capsule text-primary"></i>
                            <span>
                                <strong>{{ $item->medicine?->name }}</strong>
                                @if($item->dosage) — {{ $item->dosage }} @endif
                                @if($item->frequency) ({{ $item->frequency }}) @endif
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @else
            <div class="alert alert-info">{{ __('prescriptions.no_prescriptions') }}</div>
            @endif
        </div>
        <div class="card-footer text-center text-muted small">
            {{ __('app.name') }} &mdash; {{ now()->format('Y-m-d H:i') }}
        </div>
    </div>
</div>
</body>
</html>
