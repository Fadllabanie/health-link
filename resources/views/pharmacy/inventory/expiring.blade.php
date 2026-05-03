@extends('layouts.app')

@section('title', __('pharmacies.expiry_report'))

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center gap-3">
        <a href="{{ route('pharmacy.dashboard') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('pharmacies.expiry_report') }}</h5>
    </div>
    <div class="card-body">

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-4" id="expiryTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-expired">
                    {{ __('pharmacies.already_expired') }}
                    <span class="badge bg-danger ms-1">{{ $expired->count() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-30">
                    {{ __('pharmacies.expiring_within_30') }}
                    <span class="badge bg-warning ms-1">{{ $within30->count() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-60">
                    {{ __('pharmacies.expiring_within_60') }}
                    <span class="badge bg-info ms-1">{{ $within60->count() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-90">
                    {{ __('pharmacies.expiring_within_90') }}
                    <span class="badge bg-secondary ms-1">{{ $within90->count() }}</span>
                </button>
            </li>
        </ul>

        <div class="tab-content">
            @foreach([
                ['id' => 'expired', 'items' => $expired, 'label' => __('pharmacies.already_expired')],
                ['id' => '30', 'items' => $within30, 'label' => __('pharmacies.expiring_within_30')],
                ['id' => '60', 'items' => $within60, 'label' => __('pharmacies.expiring_within_60')],
                ['id' => '90', 'items' => $within90, 'label' => __('pharmacies.expiring_within_90')],
            ] as $i => $tab)
            <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="tab-{{ $tab['id'] }}">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('pharmacies.medicine') }}</th>
                                <th>{{ __('pharmacies.batch_number') }}</th>
                                <th>{{ __('pharmacies.quantity') }}</th>
                                <th>{{ __('pharmacies.expiry_date') }}</th>
                                <th>{{ __('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tab['items'] as $item)
                                <tr>
                                    <td>{{ $item->medicine?->name ?? '—' }}</td>
                                    <td><code>{{ $item->batch_number }}</code></td>
                                    <td>{{ $item->quantity_in_stock }}</td>
                                    <td>{{ $item->expiry_date?->format('Y-m-d') ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('pharmacy.inventory.show', $item) }}"
                                           class="btn btn-sm btn-outline-primary">{{ __('app.show') }}</a>
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
            @endforeach
        </div>

    </div>
</div>
@endsection
