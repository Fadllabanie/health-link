@extends('layouts.app')

@section('title', __('pharmacies.movement_report'))

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center gap-3">
        <a href="{{ route('pharmacy.dashboard') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('pharmacies.movement_report') }}</h5>
    </div>

    <div class="card-body border-bottom pb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">{{ __('app.date_from') }}</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('app.date_to') }}</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">{{ __('app.search') }}</button>
                <a href="{{ route('pharmacy.reports.movements') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('pharmacies.medicine') }}</th>
                    <th>{{ __('pharmacies.batch_number') }}</th>
                    <th>{{ __('pharmacies.movement_type') }}</th>
                    <th>{{ __('pharmacies.quantity') }}</th>
                    <th>{{ __('app.notes') }}</th>
                    <th>{{ __('app.performed_by') }}</th>
                    <th>{{ __('app.date') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $movement)
                    <tr>
                        <td>{{ $movement->pharmacyInventory?->medicine?->name ?? '—' }}</td>
                        <td><code>{{ $movement->pharmacyInventory?->batch_number ?? '—' }}</code></td>
                        <td>
                            @php $typeMap = [
                                'purchase' => ['label' => __('pharmacies.movement_type_purchase'), 'color' => 'success'],
                                'sale' => ['label' => __('pharmacies.movement_type_sale'), 'color' => 'primary'],
                                'return' => ['label' => __('pharmacies.movement_type_return'), 'color' => 'info'],
                                'adjustment' => ['label' => __('pharmacies.movement_type_adjustment'), 'color' => 'warning'],
                                'expired' => ['label' => __('pharmacies.movement_type_expired'), 'color' => 'danger'],
                                'transfer' => ['label' => __('pharmacies.movement_type_transfer'), 'color' => 'secondary'],
                            ]; @endphp
                            @php $t = $typeMap[$movement->type?->value] ?? ['label' => $movement->type?->value, 'color' => 'secondary']; @endphp
                            <span class="badge bg-label-{{ $t['color'] }}">{{ $t['label'] }}</span>
                        </td>
                        <td>{{ $movement->quantity }}</td>
                        <td>{{ $movement->notes ?? '—' }}</td>
                        <td>{{ $movement->performer?->first_name }} {{ $movement->performer?->last_name }}</td>
                        <td>{{ $movement->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">—</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $movements->links() }}
    </div>
</div>
@endsection
