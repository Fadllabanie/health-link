@extends('layouts.app')

@section('title', __('prescriptions.latest_prescription'))

@section('content')
<div class="row">
    <div class="col-12 mb-4 d-flex align-items-center gap-3">
        <a href="{{ route('patient.dashboard') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('prescriptions.latest_prescription') }}</h5>
    </div>

    @if($prescription)
        @include('patient.prescriptions._prescription_detail', ['prescription' => $prescription])
        <div class="col-12 mt-2">
            <a href="{{ route('patient.prescriptions.index') }}" class="btn btn-outline-secondary btn-sm">
                {{ __('prescriptions.all_prescriptions') }}
            </a>
        </div>
    @else
        <div class="col-12">
            <div class="alert alert-info">{{ __('prescriptions.no_prescriptions') }}</div>
        </div>
    @endif
</div>
@endsection
