@extends('layouts.app')

@section('title', __('master_data.cities'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">{{ __('master_data.cities') }}</h4>
    <a href="{{ route('super-admin.master-data.cities.create') }}" class="btn btn-primary">
        <i class="iconify me-1" data-icon="tabler:plus"></i>
        {{ __('master_data.add_new') }}
    </a>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">{{ __('master_data.filter_country') }}</label>
                <select name="country_id" class="form-select">
                    <option value="">{{ __('master_data.all_countries') }}</option>
                    @foreach($countries as $c)
                        <option value="{{ $c->id }}" {{ request('country_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('app.search') }}</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="اسم المدينة...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">{{ __('app.filter') }}</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('super-admin.master-data.cities.index') }}" class="btn btn-outline-secondary w-100">إعادة تعيين</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('master_data.city_name') }}</th>
                    <th>{{ __('master_data.country_name') }}</th>
                    <th>{{ __('master_data.latitude') }}</th>
                    <th>{{ __('master_data.longitude') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cities as $city)
                    <tr>
                        <td>{{ $city->id }}</td>
                        <td>{{ $city->name }}</td>
                        <td>{{ $city->country->name }}</td>
                        <td>{{ $city->latitude }}</td>
                        <td>{{ $city->longitude }}</td>
                        <td>
                            @if($city->is_active)
                                <span class="badge bg-label-success">{{ __('app.active') }}</span>
                            @else
                                <span class="badge bg-label-danger">{{ __('app.inactive') }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('super-admin.master-data.cities.edit', $city) }}" class="btn btn-sm btn-info" title="{{ __('app.edit') }}">
                                <i class="iconify" data-icon="tabler:edit"></i>
                            </a>
                            <form method="POST" action="{{ route('super-admin.master-data.cities.toggle', $city) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm {{ $city->is_active ? 'btn-warning' : 'btn-success' }}">
                                    <i class="iconify" data-icon="{{ $city->is_active ? 'tabler:toggle-right' : 'tabler:toggle-left' }}"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('super-admin.master-data.cities.destroy', $city) }}" class="d-inline"
                                  onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="iconify" data-icon="tabler:trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">لا توجد مدن.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $cities->links() }}
    </div>
</div>
@endsection
