@extends('layouts.app')

@section('title', __('prescriptions.prescriptions'))

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ __('prescriptions.prescriptions') }}</h5>
    </div>

    <div class="card-body border-bottom pb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">{{ __('app.search') }}</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="{{ __('prescriptions.search_placeholder') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('prescriptions.status') }}</label>
                <select name="status" class="form-select">
                    <option value="">-- {{ __('app.all') }} --</option>
                    <option value="pending" @selected(request('status') === 'pending')>{{ __('prescriptions.pending') }}</option>
                    <option value="partially_dispensed" @selected(request('status') === 'partially_dispensed')>{{ __('prescriptions.partially_dispensed') }}</option>
                    <option value="dispensed" @selected(request('status') === 'dispensed')>{{ __('prescriptions.dispensed') }}</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>{{ __('prescriptions.cancelled') }}</option>
                    <option value="expired" @selected(request('status') === 'expired')>{{ __('prescriptions.expired') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('prescriptions.doctor') }}</label>
                <select name="doctor_id" class="form-select">
                    <option value="">-- {{ __('app.all') }} --</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @selected(request('doctor_id') == $doctor->id)>
                            {{ $doctor->user?->first_name }} {{ $doctor->user?->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('prescriptions.pharmacy') }}</label>
                <select name="pharmacy_id" class="form-select">
                    <option value="">-- {{ __('app.all') }} --</option>
                    @foreach($pharmacies as $pharmacy)
                        <option value="{{ $pharmacy->id }}" @selected(request('pharmacy_id') == $pharmacy->id)>
                            {{ $pharmacy->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('app.date_from') }}</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('app.date_to') }}</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">{{ __('app.search') }}</button>
                <a href="{{ route('hospital-admin.prescriptions.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('prescriptions.prescription_number') }}</th>
                    <th>{{ __('prescriptions.patient') }}</th>
                    <th>{{ __('prescriptions.doctor') }}</th>
                    <th>{{ __('prescriptions.pharmacy') }}</th>
                    <th>{{ __('prescriptions.items_count') }}</th>
                    <th>{{ __('prescriptions.status') }}</th>
                    <th>{{ __('app.date') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prescriptions as $prescription)
                    <tr>
                        <td><code>{{ $prescription->prescription_number }}</code></td>
                        <td>{{ $prescription->patient?->user?->first_name }} {{ $prescription->patient?->user?->last_name }}</td>
                        <td>{{ $prescription->doctor?->user?->first_name }} {{ $prescription->doctor?->user?->last_name }}</td>
                        <td>{{ $prescription->pharmacy?->name ?? '—' }}</td>
                        <td>{{ $prescription->items_count ?? $prescription->items->count() }}</td>
                        <td>
                            @php $statusMap = [
                                'pending' => 'warning',
                                'partially_dispensed' => 'info',
                                'dispensed' => 'success',
                                'cancelled' => 'secondary',
                                'expired' => 'danger',
                            ]; @endphp
                            <span class="badge bg-label-{{ $statusMap[$prescription->status?->value] ?? 'secondary' }}">
                                {{ __('prescriptions.' . ($prescription->status?->value ?? 'pending')) }}
                            </span>
                        </td>
                        <td>{{ $prescription->created_at?->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('hospital-admin.prescriptions.show', $prescription) }}"
                               class="btn btn-sm btn-outline-primary">{{ __('app.show') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">{{ __('prescriptions.no_prescriptions') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $prescriptions->links() }}
    </div>
</div>
@endsection
