@extends('layouts.app')

@section('title', $pharmacy->name)

@section('content')
<div class="row g-4">
    {{-- Pharmacy Details --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('hospital-admin.pharmacies.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i>
                    </a>
                    <h5 class="mb-0">{{ $pharmacy->name }}</h5>
                </div>
                <a href="{{ route('hospital-admin.pharmacies.edit', $pharmacy) }}" class="btn btn-sm btn-outline-secondary">
                    <span class="iconify" data-icon="tabler:edit"></span>
                    {{ __('app.edit') }}
                </a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.license_number') }}:</strong><br>
                        <code>{{ $pharmacy->license_number }}</code>
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.email') }}:</strong><br>
                        {{ $pharmacy->email }}
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.phone') }}:</strong><br>
                        {{ $pharmacy->phone }}
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.type') }}:</strong><br>
                        @if($pharmacy->type?->value === 'in_hospital')
                            <span class="badge bg-label-info">{{ __('pharmacies.pharmacy_type_in_hospital') }}</span>
                        @elseif($pharmacy->type?->value === 'external')
                            <span class="badge bg-label-secondary">{{ __('pharmacies.pharmacy_type_external') }}</span>
                        @else
                            <span class="badge bg-label-warning">{{ __('pharmacies.pharmacy_type_chain') }}</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.status') }}:</strong><br>
                        @if($pharmacy->status?->value === 'active')
                            <span class="badge bg-label-success">{{ __('pharmacies.status_active') }}</span>
                        @elseif($pharmacy->status?->value === 'inactive')
                            <span class="badge bg-label-secondary">{{ __('pharmacies.status_inactive') }}</span>
                        @else
                            <span class="badge bg-label-danger">{{ __('pharmacies.status_suspended') }}</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('pharmacies.is_24_hours') }}:</strong><br>
                        {{ $pharmacy->is_24_hours ? __('app.yes') : __('app.no') }}
                        @if(!$pharmacy->is_24_hours && $pharmacy->opening_time)
                            ({{ $pharmacy->opening_time }} — {{ $pharmacy->closing_time }})
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('app.city') }}:</strong><br>
                        {{ $pharmacy->city?->name ?? '—' }}, {{ $pharmacy->country?->name ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('pharmacies.address') }}:</strong><br>
                        {{ $pharmacy->address }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pharmacists --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('pharmacies.pharmacists') }}</h5>
                <a href="{{ route('hospital-admin.pharmacies.pharmacists.create', $pharmacy) }}" class="btn btn-sm btn-primary">
                    <span class="iconify" data-icon="tabler:plus"></span>
                    {{ __('pharmacies.add_pharmacist') }}
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('app.name') }}</th>
                            <th>{{ __('app.email') }}</th>
                            <th>{{ __('pharmacies.license_number') }}</th>
                            <th>{{ __('pharmacies.location') }}</th>
                            <th>{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pharmacy->pharmacists as $pharmacist)
                            <tr>
                                <td>{{ $pharmacist->user?->first_name }} {{ $pharmacist->user?->last_name }}</td>
                                <td>{{ $pharmacist->user?->email }}</td>
                                <td>{{ $pharmacist->license_number ?? '—' }}</td>
                                <td>{{ $pharmacist->position ?? '—' }}</td>
                                <td>
                                    <form method="POST"
                                          action="{{ route('hospital-admin.pharmacies.pharmacists.destroy', [$pharmacy, $pharmacist]) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('app.disable') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">{{ __('pharmacies.no_pharmacists') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Inventory Summary --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('pharmacies.inventory') }}</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('pharmacies.medicine') }}</th>
                            <th>{{ __('pharmacies.batch_number') }}</th>
                            <th>{{ __('pharmacies.quantity') }}</th>
                            <th>{{ __('pharmacies.expiry_date') }}</th>
                            <th>{{ __('pharmacies.inventory_status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pharmacy->inventories as $item)
                            <tr>
                                <td>{{ $item->medicine?->name ?? '—' }}</td>
                                <td><code>{{ $item->batch_number }}</code></td>
                                <td>{{ $item->quantity_in_stock }}</td>
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
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">{{ __('pharmacies.no_inventory') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
