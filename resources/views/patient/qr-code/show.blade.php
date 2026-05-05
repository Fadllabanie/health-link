@extends('layouts.app')

@section('title', __('patients.my_qr_code'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-header d-flex align-items-center gap-3">
                <a href="{{ route('patient.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-arrow-back"></i>
                </a>
                <h6 class="mb-0">{{ __('patients.my_qr_code') }}</h6>
            </div>
            <div class="card-body py-5">
                @if($patient->qrCode && $patient->qrCode->image_path)
                    <div class="mb-4">
                        <img
                            id="qr-image"
                            src="{{ asset('storage/'.$patient->qrCode->image_path) }}"
                            alt="{{ __('patients.qr_code') }}"
                            class="img-fluid"
                            style="max-width: 280px;"
                        >
                    </div>
                    <p class="text-muted small mb-1">{{ __('patients.scan_count') }}: {{ $patient->qrCode->scan_count }}</p>
                    @if($patient->qrCode->last_scanned_at)
                        <p class="text-muted small mb-3">{{ __('patients.last_scanned') }}: {{ $patient->qrCode->last_scanned_at->diffForHumans() }}</p>
                    @endif

                    <div class="d-flex justify-content-center gap-3">
                        <a
                            href="{{ asset('storage/'.$patient->qrCode->image_path) }}"
                            download="qr-{{ $patient->medical_record_number }}.svg"
                            class="btn btn-outline-primary"
                        >
                            <i class="bx bx-download me-1"></i>{{ __('app.download') }}
                        </a>

                        <form method="POST" action="{{ route('patient.qr-code.regenerate') }}"
                              onsubmit="return confirm('{{ __('patients.confirm_regenerate_qr') }}')">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning">
                                <i class="bx bx-refresh me-1"></i>{{ __('patients.regenerate_qr') }}
                            </button>
                        </form>
                    </div>
                @else
                    <p class="text-muted">{{ __('patients.no_qr_code') }}</p>
                    <form method="POST" action="{{ route('patient.qr-code.regenerate') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-qr me-1"></i>{{ __('patients.generate_qr') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
