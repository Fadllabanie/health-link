@extends('layouts.app')

@section('title', __('medical_records.edit_record'))

@section('content')
<div class="row">
    <div class="col-12 mb-4 d-flex align-items-center gap-3">
        <a href="{{ route('doctor.patients.medical-history', $patient) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('medical_records.edit_record') }}</h5>
        <small class="text-muted">
            {{ $patient->user->first_name }} {{ $patient->user->last_name }}
            ({{ $patient->medical_record_number }})
        </small>
    </div>

    @if($medicalRecord->status !== \App\Enums\RecordStatus::Draft)
    <div class="col-12 mb-3">
        <div class="alert alert-warning">
            <i class="bx bx-info-circle me-1"></i>
            {{ __('medical_records.edit_window_note') }}
        </div>
    </div>
    @endif

    <div class="col-lg-9">
        <form method="POST"
              action="{{ route('doctor.patients.medical-records.update', [$patient, $medicalRecord]) }}"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('doctor.medical-records._form', ['visitTypes' => $visitTypes, 'medicalRecord' => $medicalRecord])

            @if($medicalRecord->attachments->count())
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">{{ __('medical_records.attachments') }}</h6></div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            @foreach($medicalRecord->attachments as $attachment)
                            <tr>
                                <td>{{ $attachment->file_name }}</td>
                                <td>{{ number_format($attachment->file_size / 1024, 1) }} KB</td>
                                <td>
                                    <a href="{{ $attachment->file_url }}" target="_blank" class="btn btn-xs btn-outline-primary">
                                        <i class="bx bx-download"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

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
