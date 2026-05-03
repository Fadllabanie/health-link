@extends('layouts.app')

@section('title', __('master_data.specialties'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">{{ __('master_data.specialties') }}</h4>
    <a href="{{ route('super-admin.master-data.specialties.create') }}" class="btn btn-primary">
        <i class="iconify me-1" data-icon="tabler:plus"></i>
        {{ __('master_data.add_new') }}
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label">{{ __('app.search') }}</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="اسم التخصص...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">{{ __('app.filter') }}</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('super-admin.master-data.specialties.index') }}" class="btn btn-outline-secondary w-100">إعادة تعيين</a>
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
                    <th>{{ __('master_data.specialty_icon') }}</th>
                    <th>{{ __('master_data.specialty_name') }}</th>
                    <th>{{ __('master_data.specialty_slug') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($specialties as $specialty)
                    <tr class="{{ $specialty->trashed() ? 'table-warning' : '' }}">
                        <td>{{ $specialty->id }}</td>
                        <td>
                            @if($specialty->icon)
                                <img src="{{ Storage::url($specialty->icon) }}" width="36" height="36" class="rounded" alt="">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $specialty->name }}</td>
                        <td><code>{{ $specialty->slug }}</code></td>
                        <td>
                            @if($specialty->trashed())
                                <span class="badge bg-label-secondary">{{ __('master_data.archived') }}</span>
                            @elseif($specialty->is_active)
                                <span class="badge bg-label-success">{{ __('app.active') }}</span>
                            @else
                                <span class="badge bg-label-danger">{{ __('app.inactive') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($specialty->trashed())
                                <form method="POST" action="{{ route('super-admin.master-data.specialties.restore', $specialty->id) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-warning">
                                        <i class="iconify" data-icon="tabler:restore"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('super-admin.master-data.specialties.edit', $specialty) }}" class="btn btn-sm btn-info">
                                    <i class="iconify" data-icon="tabler:edit"></i>
                                </a>
                                <form method="POST" action="{{ route('super-admin.master-data.specialties.toggle', $specialty) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm {{ $specialty->is_active ? 'btn-warning' : 'btn-success' }}">
                                        <i class="iconify" data-icon="{{ $specialty->is_active ? 'tabler:toggle-right' : 'tabler:toggle-left' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('super-admin.master-data.specialties.destroy', $specialty) }}" class="d-inline"
                                      onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="iconify" data-icon="tabler:archive"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">لا توجد تخصصات.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $specialties->links() }}
    </div>
</div>
@endsection
