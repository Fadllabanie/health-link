@extends('layouts.app')

@section('title', __('patients.patients'))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('patients.patients') }}</h5>
    </div>

    <div class="card-body border-bottom pb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="{{ __('patients.search_placeholder') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">{{ __('app.search') }}</button>
                <a href="{{ route('doctor.patients.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('patients.patient') }}</th>
                    <th>{{ __('patients.medical_record_number') }}</th>
                    <th>{{ __('app.gender') }}</th>
                    <th>{{ __('patients.blood_type') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr>
                        <td>{{ $patient->user->full_name }}</td>
                        <td><code>{{ $patient->medical_record_number }}</code></td>
                        <td>{{ $patient->user->gender ? __('app.'.$patient->user->gender) : '—' }}</td>
                        <td>{{ $patient->blood_type?->value ?? '—' }}</td>
                        <td>
                            <a href="{{ route('doctor.patients.show', $patient) }}" class="btn btn-sm btn-outline-primary">
                                {{ __('app.show') }}
                            </a>
                            <a href="{{ route('doctor.patients.medical-history', $patient) }}" class="btn btn-sm btn-outline-secondary">
                                {{ __('patients.medical_history') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">{{ __('patients.no_patients') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">{{ $patients->links() }}</div>
</div>
@endsection
