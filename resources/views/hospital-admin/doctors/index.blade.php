@extends('layouts.app')

@section('title', __('doctors.doctors'))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">{{ __('doctors.doctors') }}</h5>
        <a href="{{ route('hospital-admin.doctors.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i>{{ __('doctors.add_doctor') }}
        </a>
    </div>

    {{-- Filters --}}
    <div class="card-body border-bottom pb-3">
        <form method="GET" class="row g-2">
            <div class="col-12 col-sm-4">
                <input type="text" name="search" class="form-control"
                    placeholder="{{ __('app.search') }}" value="{{ request('search') }}">
            </div>
            <div class="col-6 col-sm-3">
                <select name="specialty" class="form-select">
                    <option value="">{{ __('doctors.filter_specialty') }}</option>
                    @foreach($specialties as $s)
                        <option value="{{ $s->id }}" @selected(request('specialty') == $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-3">
                <select name="department" class="form-select">
                    <option value="">{{ __('doctors.filter_department') }}</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" @selected(request('department') == $d->id)>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-2">
                <select name="status" class="form-select">
                    <option value="">{{ __('doctors.filter_status') }}</option>
                    <option value="active" @selected(request('status') === 'active')>{{ __('doctors.active') }}</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>{{ __('doctors.inactive') }}</option>
                    <option value="on_leave" @selected(request('status') === 'on_leave')>{{ __('doctors.on_leave') }}</option>
                </select>
            </div>
            <div class="col-6 col-sm-auto">
                <button type="submit" class="btn btn-outline-secondary w-100">{{ __('app.filter') }}</button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('app.name') }}</th>
                    <th>{{ __('doctors.primary_specialty') }}</th>
                    <th>{{ __('doctors.department') }}</th>
                    <th>{{ __('doctors.license_number') }}</th>
                    <th>{{ __('doctors.status') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <strong>{{ $doctor->name }}</strong>
                                    <div class="small text-muted">{{ $doctor->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $doctor->primarySpecialty?->name ?? '—' }}</td>
                        <td>{{ $doctor->department?->name ?? '—' }}</td>
                        <td>{{ $doctor->license_number }}</td>
                        <td>
                            @if($doctor->status->value === 'active')
                                <span class="badge bg-label-success">{{ __('doctors.active') }}</span>
                            @elseif($doctor->status->value === 'inactive')
                                <span class="badge bg-label-danger">{{ __('doctors.inactive') }}</span>
                            @else
                                <span class="badge bg-label-warning">{{ __('doctors.on_leave') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('hospital-admin.doctors.show', $doctor) }}"
                                    class="btn btn-sm btn-icon btn-outline-secondary" title="{{ __('app.view') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('hospital-admin.doctors.edit', $doctor) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary" title="{{ __('app.edit') }}">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form method="POST"
                                    action="{{ route('hospital-admin.doctors.toggle-status', $doctor) }}"
                                    class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-warning"
                                        title="{{ $doctor->status->value === 'active' ? __('doctors.disable_doctor') : __('doctors.enable_doctor') }}">
                                        <i class="bx {{ $doctor->status->value === 'active' ? 'bx-lock' : 'bx-lock-open' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">{{ __('doctors.no_doctors') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $doctors->links() }}
    </div>
</div>
@endsection
