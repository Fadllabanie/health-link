@extends('layouts.app')

@section('title', __('master_data.countries'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">{{ __('master_data.countries') }}</h4>
    <a href="{{ route('super-admin.master-data.countries.create') }}" class="btn btn-primary">
        <i class="iconify me-1" data-icon="tabler:plus"></i>
        {{ __('master_data.add_new') }}
    </a>
</div>

<div class="card">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('master_data.country_name') }}</th>
                    <th>{{ __('master_data.country_code') }}</th>
                    <th>{{ __('master_data.country_code3') }}</th>
                    <th>{{ __('master_data.phone_code') }}</th>
                    <th>{{ __('master_data.currency_code') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($countries as $country)
                    <tr class="{{ $country->trashed() ? 'table-warning' : '' }}">
                        <td>{{ $country->id }}</td>
                        <td>{{ $country->name }}</td>
                        <td><span class="badge bg-label-info">{{ $country->code }}</span></td>
                        <td>{{ $country->code3 }}</td>
                        <td>{{ $country->phone_code }}</td>
                        <td>{{ $country->currency_code }}</td>
                        <td>
                            @if($country->trashed())
                                <span class="badge bg-label-secondary">{{ __('master_data.archived') }}</span>
                            @elseif($country->is_active)
                                <span class="badge bg-label-success">{{ __('app.active') }}</span>
                            @else
                                <span class="badge bg-label-danger">{{ __('app.inactive') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($country->trashed())
                                <form method="POST" action="{{ route('super-admin.master-data.countries.restore', $country->id) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-warning" title="{{ __('master_data.restore') }}">
                                        <i class="iconify" data-icon="tabler:restore"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('super-admin.master-data.countries.edit', $country) }}" class="btn btn-sm btn-info" title="{{ __('app.edit') }}">
                                    <i class="iconify" data-icon="tabler:edit"></i>
                                </a>
                                <form method="POST" action="{{ route('super-admin.master-data.countries.toggle', $country) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm {{ $country->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ __('app.status') }}">
                                        <i class="iconify" data-icon="{{ $country->is_active ? 'tabler:toggle-right' : 'tabler:toggle-left' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('super-admin.master-data.countries.destroy', $country) }}" class="d-inline"
                                      onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="{{ __('app.delete') }}">
                                        <i class="iconify" data-icon="tabler:archive"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">لا توجد دول.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $countries->links() }}
    </div>
</div>
@endsection
