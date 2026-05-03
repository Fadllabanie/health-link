@extends('layouts.app')

@section('title', __('pharmacies.stock_report'))

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center gap-3">
        <a href="{{ route('pharmacy.dashboard') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('pharmacies.stock_report') }} — {{ $pharmacy->name }}</h5>
    </div>
    <div class="card-body">
        @forelse($items as $category => $categoryItems)
            <h6 class="fw-semibold text-primary mb-3 mt-3">{{ $category }}</h6>
            <div class="table-responsive mb-4">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('pharmacies.medicine') }}</th>
                            <th>{{ __('pharmacies.batch_number') }}</th>
                            <th>{{ __('pharmacies.quantity') }}</th>
                            <th>{{ __('pharmacies.reorder_level') }}</th>
                            <th>{{ __('pharmacies.selling_price') }}</th>
                            <th>{{ __('pharmacies.expiry_date') }}</th>
                            <th>{{ __('pharmacies.inventory_status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categoryItems as $item)
                            <tr>
                                <td>{{ $item->medicine?->name ?? '—' }}</td>
                                <td><code>{{ $item->batch_number }}</code></td>
                                <td>{{ $item->quantity_in_stock }}</td>
                                <td>{{ $item->reorder_level }}</td>
                                <td>{{ number_format($item->selling_price, 2) }}</td>
                                <td>{{ $item->expiry_date?->format('Y-m-d') ?? '—' }}</td>
                                <td>
                                    @if($item->status?->value === 'available')
                                        <span class="badge bg-label-success">{{ __('pharmacies.status_available') }}</span>
                                    @elseif($item->status?->value === 'low_stock')
                                        <span class="badge bg-label-warning">{{ __('pharmacies.status_low_stock') }}</span>
                                    @elseif($item->status?->value === 'out_of_stock')
                                        <span class="badge bg-label-secondary">{{ __('pharmacies.status_out_of_stock') }}</span>
                                    @else
                                        <span class="badge bg-label-danger">{{ __('pharmacies.status_expired') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <p class="text-muted text-center py-4">{{ __('pharmacies.no_inventory') }}</p>
        @endforelse
    </div>
</div>
@endsection
