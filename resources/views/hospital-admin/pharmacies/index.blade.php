@extends('layouts.app')

@section('title', __('pharmacies.pharmacies'))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('pharmacies.pharmacies') }}</h5>
        <a href="{{ route('hospital-admin.pharmacies.create') }}" class="btn btn-primary btn-sm">
            <span class="iconify" data-icon="tabler:plus"></span>
            {{ __('pharmacies.add_pharmacy') }}
        </a>
    </div>

    <div class="card-body border-bottom pb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">{{ __('app.search') }}</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="{{ __('pharmacies.pharmacy_name') }} / {{ __('pharmacies.license_number') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">{{ __('app.search') }}</button>
                <a href="{{ route('hospital-admin.pharmacies.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('pharmacies.pharmacy_name') }}</th>
                    <th>{{ __('pharmacies.license_number') }}</th>
                    <th>{{ __('pharmacies.type') }}</th>
                    <th>{{ __('pharmacies.status') }}</th>
                    <th>{{ __('app.city') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pharmacies as $pharmacy)
                    <tr>
                        <td>{{ $pharmacy->id }}</td>
                        <td>
                            <a href="{{ route('hospital-admin.pharmacies.show', $pharmacy) }}">
                                {{ $pharmacy->name }}
                            </a>
                        </td>
                        <td><code>{{ $pharmacy->license_number }}</code></td>
                        <td>
                            @if($pharmacy->type?->value === 'in_hospital')
                                <span class="badge bg-label-info">{{ __('pharmacies.pharmacy_type_in_hospital') }}</span>
                            @elseif($pharmacy->type?->value === 'external')
                                <span class="badge bg-label-secondary">{{ __('pharmacies.pharmacy_type_external') }}</span>
                            @else
                                <span class="badge bg-label-warning">{{ __('pharmacies.pharmacy_type_chain') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($pharmacy->status?->value === 'active')
                                <span class="badge bg-label-success">{{ __('pharmacies.status_active') }}</span>
                            @elseif($pharmacy->status?->value === 'inactive')
                                <span class="badge bg-label-secondary">{{ __('pharmacies.status_inactive') }}</span>
                            @else
                                <span class="badge bg-label-danger">{{ __('pharmacies.status_suspended') }}</span>
                            @endif
                        </td>
                        <td>{{ $pharmacy->city?->name ?? '—' }}</td>
                        <td>
                            <a href="{{ route('hospital-admin.pharmacies.show', $pharmacy) }}"
                               class="btn btn-sm btn-outline-primary">{{ __('app.show') }}</a>
                            <a href="{{ route('hospital-admin.pharmacies.edit', $pharmacy) }}"
                               class="btn btn-sm btn-outline-secondary">{{ __('app.edit') }}</a>
                            <form method="POST" action="{{ route('hospital-admin.pharmacies.destroy', $pharmacy) }}"
                                  class="d-inline" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('app.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">{{ __('pharmacies.no_pharmacies') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $pharmacies->links() }}
    </div>
</div>
@endsection
