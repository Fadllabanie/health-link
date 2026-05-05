@extends('layouts.app')

@section('title', __('patients.patients'))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('patients.patients') }}</h5>
        {{-- <a href="{{ route('hospital-admin.patients.create') }}" class="btn btn-primary btn-sm">
            <span class="iconify" data-icon="tabler:plus"></span>
            {{ __('patients.add_patient') }}
        </a> --}}
    </div>

    <div class="card-body border-bottom pb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">{{ __('app.search') }}</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="{{ __('app.name') }} / {{ __('patients.medical_record_number') }} / {{ __('app.phone') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">{{ __('app.search') }}</button>
                <a href="{{ route('hospital-admin.patients.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('app.name') }}</th>
                    <th>{{ __('patients.medical_record_number') }}</th>
                    <th>{{ __('app.phone') }}</th>
                    <th>{{ __('patients.blood_type') }}</th>
                    <th>{{ __('app.city') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr>
                        <td>{{ $patient->id }}</td>
                        <td>
                            <a href="{{ route('hospital-admin.patients.show', $patient) }}">
                                {{ $patient->user->first_name }} {{ $patient->user->last_name }}
                            </a>
                        </td>
                        <td><code>{{ $patient->medical_record_number }}</code></td>
                        <td>{{ $patient->user->phone ?? '—' }}</td>
                        <td>{{ $patient->blood_type?->value ?? '—' }}</td>
                        <td>{{ $patient->city?->name ?? '—' }}</td>
                        <td>
                            <a href="{{ route('hospital-admin.patients.show', $patient) }}"
                               class="btn btn-sm btn-outline-primary">{{ __('app.show') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">{{ __('patients.no_patients') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $patients->links() }}
    </div>
</div>
@endsection
