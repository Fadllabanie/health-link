@extends('layouts.app')

@section('title', __('pharmacies.inventory'))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('hospital-admin.pharmacies.show', $pharmacy) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-arrow-back"></i>
            </a>
            <h5 class="mb-0">{{ __('pharmacies.inventory_items') }} — {{ $pharmacy->name }}</h5>
        </div>
        <a href="{{ route('hospital-admin.pharmacies.inventory.create', $pharmacy) }}" class="btn btn-primary btn-sm">
            <span class="iconify" data-icon="tabler:plus"></span>
            {{ __('pharmacies.add_inventory') }}
        </a>
    </div>

    <div class="card-body border-bottom pb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label">{{ __('app.search') }}</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="{{ __('pharmacies.medicine') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('pharmacies.inventory_status') }}</label>
                <select name="status" class="form-select">
                    <option value="">-- {{ __('app.all') }} --</option>
                    <option value="available" @selected(request('status') === 'available')>{{ __('pharmacies.status_available') }}</option>
                    <option value="low_stock" @selected(request('status') === 'low_stock')>{{ __('pharmacies.status_low_stock') }}</option>
                    <option value="out_of_stock" @selected(request('status') === 'out_of_stock')>{{ __('pharmacies.status_out_of_stock') }}</option>
                    <option value="expired" @selected(request('status') === 'expired')>{{ __('pharmacies.status_expired') }}</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">{{ __('app.search') }}</button>
                <a href="{{ route('hospital-admin.pharmacies.inventory.index', $pharmacy) }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>

    <div class="table-responsive text-nowrap">
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
                        <td>{{ $item->quantity_in_stock }}</td>
                        <td>{{ $item->reorder_level }}</td>
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
                        <td>
                            <a href="{{ route('hospital-admin.pharmacies.inventory.show', [$pharmacy, $item]) }}"
                               class="btn btn-sm btn-outline-primary">{{ __('app.show') }}</a>
                            <a href="{{ route('hospital-admin.pharmacies.inventory.edit', [$pharmacy, $item]) }}"
                               class="btn btn-sm btn-outline-secondary">{{ __('app.edit') }}</a>
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

    <div class="card-footer">
        {{ $items->links() }}
    </div>
</div>
@endsection
