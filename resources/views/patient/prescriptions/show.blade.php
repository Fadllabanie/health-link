@extends('layouts.app')

@section('title', $prescription->prescription_number)

@section('content')
<div class="row">
    <div class="col-12 mb-4 d-flex align-items-center gap-3">
        <a href="{{ route('patient.prescriptions.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('prescriptions.prescription') }}: <code>{{ $prescription->prescription_number }}</code></h5>
    </div>

    @include('patient.prescriptions._prescription_detail', ['prescription' => $prescription])
</div>
@endsection
