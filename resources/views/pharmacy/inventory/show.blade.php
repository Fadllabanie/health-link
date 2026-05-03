@extends('layouts.app')

@section('title', $inventory->medicine?->name ?? __('pharmacies.inventory'))

@section('content')
<div class="row g-4">
    {{-- Item Details --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('pharmacy.inventory.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i>
                    </a>
                    <h5 class="mb-0">{{ $inventory->medicine?->name ?? __('pharmacies.inventory') }}</h5>
                </div>
                <a href="{{ route('pharmacy.inventory.edit', $inventory) }}" class="btn btn-sm btn-outline-secondary">
                    <span class="iconify" data-icon="tabler:edit"></span>
                    {{ __('app.edit') }}
                </a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.batch_number') }}:</strong><br>
                        <code>{{ $inventory->batch_number }}</code>
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.quantity') }}:</strong><br>
                        {{ $inventory->quantity_in_stock }}
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.reorder_level') }}:</strong><br>
                        {{ $inventory->reorder_level }}
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.unit_cost') }}:</strong><br>
                        {{ number_format($inventory->unit_cost, 2) }}
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.selling_price') }}:</strong><br>
                        {{ number_format($inventory->selling_price, 2) }}
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.inventory_status') }}:</strong><br>
                        @if($inventory->status?->value === 'available')
                            <span class="badge bg-label-success">{{ __('pharmacies.status_available') }}</span>
                        @elseif($inventory->status?->value === 'low_stock')
                            <span class="badge bg-label-warning">{{ __('pharmacies.status_low_stock') }}</span>
                        @elseif($inventory->status?->value === 'out_of_stock')
                            <span class="badge bg-label-secondary">{{ __('pharmacies.status_out_of_stock') }}</span>
                        @else
                            <span class="badge bg-label-danger">{{ __('pharmacies.status_expired') }}</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.manufacturing_date') }}:</strong><br>
                        {{ $inventory->manufacturing_date?->format('Y-m-d') ?? '—' }}
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.expiry_date') }}:</strong><br>
                        {{ $inventory->expiry_date?->format('Y-m-d') ?? '—' }}
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.supplier') }}:</strong><br>
                        {{ $inventory->supplier ?? '—' }}
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.location') }}:</strong><br>
                        {{ $inventory->location ?? '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stock Movements --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('pharmacies.stock_movements') }}</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('pharmacies.movement_type') }}</th>
                            <th>{{ __('pharmacies.quantity') }}</th>
                            <th>{{ __('app.notes') }}</th>
                            <th>{{ __('app.performed_by') }}</th>
                            <th>{{ __('app.date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventory->stockMovements as $movement)
                            <tr>
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
                                <td colspan="5" class="text-center py-3 text-muted">—</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
