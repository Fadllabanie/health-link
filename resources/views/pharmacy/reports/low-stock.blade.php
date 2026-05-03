@extends('layouts.app')

@section('title', __('pharmacies.low_stock_report'))

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center gap-3">
        <a href="{{ route('pharmacy.dashboard') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('pharmacies.low_stock_report') }} — {{ $pharmacy->name }}</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('pharmacies.medicine') }}</th>
                    <th>{{ __('pharmacies.batch_number') }}</th>
                    <th>{{ __('pharmacies.quantity') }}</th>
                    <th>{{ __('pharmacies.reorder_level') }}</th>
                    <th>{{ __('pharmacies.expiry_date') }}</th>
                    <th>{{ __('pharmacies.inventory_status') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item->medicine?->name ?? '—' }}</td>
                        <td><code>{{ $item->batch_number }}</code></td>
                        <td class="fw-bold text-danger">{{ $item->quantity_in_stock }}</td>
                        <td>{{ $item->reorder_level }}</td>
                        <td>{{ $item->expiry_date?->format('Y-m-d') ?? '—' }}</td>
                        <td>
                            @if($item->status?->value === 'out_of_stock')
                                <span class="badge bg-label-secondary">{{ __('pharmacies.status_out_of_stock') }}</span>
                            @else
                                <span class="badge bg-label-warning">{{ __('pharmacies.status_low_stock') }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pharmacy.inventory.show', $item) }}"
                               class="btn btn-sm btn-outline-primary">{{ __('app.show') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">{{ __('pharmacies.no_inventory') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
