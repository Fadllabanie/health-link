@extends('layouts.app')

@section('title', __('master_data.departments'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">{{ __('master_data.departments') }}</h4>
    <a href="{{ route('super-admin.master-data.departments.create') }}" class="btn btn-primary">
        <i class="iconify me-1" data-icon="tabler:plus"></i>
        {{ __('master_data.add_new') }}
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">{{ __('master_data.filter_hospital') }}</label>
                <select name="hospital_id" class="form-select">
                    <option value="">{{ __('master_data.all_hospitals') }}</option>
                    @foreach($hospitals as $h)
                        <option value="{{ $h->id }}" {{ request('hospital_id') == $h->id ? 'selected' : '' }}>
                            {{ $h->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('app.search') }}</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="اسم القسم...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">{{ __('app.filter') }}</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('super-admin.master-data.departments.index') }}" class="btn btn-outline-secondary w-100">إعادة تعيين</a>
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
                    <th>{{ __('master_data.department_name') }}</th>
                    <th>{{ __('master_data.department_code') }}</th>
                    <th>المستشفى</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $dept)
                    <tr class="{{ $dept->trashed() ? 'table-warning' : '' }}">
                        <td>{{ $dept->id }}</td>
                        <td>{{ $dept->name }}</td>
                        <td>{{ $dept->code ?? '—' }}</td>
                        <td>{{ $dept->hospital->name }}</td>
                        <td>
                            @if($dept->trashed())
                                <span class="badge bg-label-secondary">{{ __('master_data.archived') }}</span>
                            @elseif($dept->is_active)
                                <span class="badge bg-label-success">{{ __('app.active') }}</span>
                            @else
                                <span class="badge bg-label-danger">{{ __('app.inactive') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($dept->trashed())
                                <form method="POST" action="{{ route('super-admin.master-data.departments.restore', $dept->id) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-warning">
                                        <i class="iconify" data-icon="tabler:restore"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('super-admin.master-data.departments.edit', $dept) }}" class="btn btn-sm btn-info">
                                    <i class="iconify" data-icon="tabler:edit"></i>
                                </a>
                                <form method="POST" action="{{ route('super-admin.master-data.departments.toggle', $dept) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm {{ $dept->is_active ? 'btn-warning' : 'btn-success' }}">
                                        <i class="iconify" data-icon="{{ $dept->is_active ? 'tabler:toggle-right' : 'tabler:toggle-left' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('super-admin.master-data.departments.destroy', $dept) }}" class="d-inline"
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
                        <td colspan="6" class="text-center py-4 text-muted">لا توجد أقسام.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $departments->links() }}
    </div>
</div>
@endsection
