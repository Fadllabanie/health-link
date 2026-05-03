@extends('layouts.app')

@section('title', __('prescriptions.prescriptions'))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('prescriptions.prescriptions') }}</h5>
        <a href="{{ route('doctor.prescriptions.create') }}" class="btn btn-primary btn-sm">
            <i class="bx bx-plus me-1"></i>{{ __('prescriptions.new_prescription') }}
        </a>
    </div>

    <div class="card-body border-bottom pb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="{{ __('prescriptions.search_placeholder') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">{{ __('app.status') }} — {{ __('app.all') ?? 'الكل' }}</option>
                    <option value="pending" @selected(request('status') === 'pending')>{{ __('prescriptions.pending') }}</option>
                    <option value="dispensed" @selected(request('status') === 'dispensed')>{{ __('prescriptions.dispensed') }}</option>
                    <option value="partially_dispensed" @selected(request('status') === 'partially_dispensed')>{{ __('prescriptions.partially_dispensed') }}</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>{{ __('prescriptions.cancelled') }}</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">{{ __('app.search') }}</button>
                <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('prescriptions.prescription_number') }}</th>
                    <th>{{ __('patients.patient') }}</th>
                    <th>{{ __('prescriptions.items_count') }}</th>
                    <th>{{ __('prescriptions.issued_at') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prescriptions as $rx)
                    <tr>
                        <td><code>{{ $rx->prescription_number }}</code></td>
                        <td>{{ $rx->patient->user->first_name }} {{ $rx->patient->user->last_name }}</td>
                        <td>{{ $rx->items->count() }}</td>
                        <td>{{ $rx->issued_at?->format('Y-m-d') }}</td>
                        <td>
                            @php
                                $badgeMap = ['pending' => 'warning', 'dispensed' => 'success', 'partially_dispensed' => 'info', 'cancelled' => 'danger'];
                                $badge = $badgeMap[$rx->status->value] ?? 'secondary';
                            @endphp
                            <span class="badge bg-label-{{ $badge }}">{{ __('prescriptions.'.$rx->status->value) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('doctor.prescriptions.show', $rx) }}" class="btn btn-sm btn-outline-primary">{{ __('app.show') }}</a>
                            @if($rx->status->value === 'pending')
                                <a href="{{ route('doctor.prescriptions.edit', $rx) }}" class="btn btn-sm btn-outline-warning">{{ __('app.edit') }}</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">{{ __('prescriptions.no_prescriptions') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">{{ $prescriptions->links() }}</div>
</div>
@endsection
