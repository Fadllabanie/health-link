@extends('layouts.app')

@section('title', __('medical_records.add_record'))

@section('content')
<div class="row">
    <div class="col-12 mb-4 d-flex align-items-center gap-3">
        <a href="{{ route('doctor.patients.medical-history', $patient) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('medical_records.add_record') }}</h5>
        <small class="text-muted">
            {{ $patient->user->first_name }} {{ $patient->user->last_name }}
            ({{ $patient->medical_record_number }})
        </small>
    </div>

    <div class="col-lg-9">
        <form method="POST"
              action="{{ route('doctor.patients.medical-records.store', $patient) }}"
              enctype="multipart/form-data">
            @csrf

            @include('doctor.medical-records._form', ['visitTypes' => $visitTypes])

            <div class="d-flex gap-2 mt-3">
                <button type="submit" name="finalize" value="0" class="btn btn-outline-primary">
                    {{ __('medical_records.save_draft') }}
                </button>
                <button type="submit" name="finalize" value="1" class="btn btn-primary">
                    {{ __('medical_records.finalize') }}
                </button>
                <a href="{{ route('doctor.patients.medical-history', $patient) }}" class="btn btn-outline-secondary ms-auto">
                    {{ __('app.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
