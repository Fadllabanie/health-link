@extends('layouts.app')

@section('title', __('hospitals.hospitals'))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('hospitals.hospitals') }}</h5>
        <a href="{{ route('super-admin.hospitals.create') }}" class="btn btn-primary btn-sm">
            <span class="iconify" data-icon="tabler:plus"></span>
            {{ __('hospitals.add_hospital') }}
        </a>
    </div>

    {{-- Filters --}}
    <div class="card-body border-bottom pb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label">{{ __('app.search') }}</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="{{ __('hospitals.name') }} / {{ __('hospitals.email') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('app.status') }}</label>
                <select name="status" class="form-select">
                    <option value="">{{ __('app.filter') }}...</option>
                    <option value="active" @selected(request('status') === 'active')>{{ __('app.active') }}</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>{{ __('app.inactive') }}</option>
                    <option value="suspended" @selected(request('status') === 'suspended')>{{ __('app.suspended') }}</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">{{ __('app.search') }}</button>
                <a href="{{ route('super-admin.hospitals.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('hospitals.name') }}</th>
                    <th>{{ __('hospitals.email') }}</th>
                    <th>{{ __('hospitals.city') }}</th>
                    <th>{{ __('hospitals.subscription_plan') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hospitals as $hospital)
                    <tr class="{{ $hospital->trashed() ? 'table-secondary text-muted' : '' }}">
                        <td>{{ $hospital->id }}</td>
                        <td>
                            <a href="{{ route('super-admin.hospitals.show', $hospital) }}">
                                {{ $hospital->name }}
                            </a>
                            @if($hospital->trashed())
                                <span class="badge bg-secondary ms-1">{{ __('app.archived') ?? 'مؤرشف' }}</span>
                            @endif
                        </td>
                        <td>{{ $hospital->email }}</td>
                        <td>{{ $hospital->city?->name }}</td>
                        <td>{{ __('hospitals.plan_' . $hospital->subscription_plan->value) }}</td>
                        <td>
                            @php $statusColor = match($hospital->status->value) {
                                'active'    => 'success',
                                'inactive'  => 'secondary',
                                'suspended' => 'danger',
                                default     => 'secondary',
                            }; @endphp
                            <span class="badge bg-{{ $statusColor }}">
                                {{ __('app.' . $hospital->status->value) }}
                            </span>
                        </td>
                        <td>
                            @unless($hospital->trashed())
                                <a href="{{ route('super-admin.hospitals.edit', $hospital) }}"
                                   class="btn btn-sm btn-outline-primary me-1">{{ __('app.edit') }}</a>

                                <form method="POST" action="{{ route('super-admin.hospitals.destroy', $hospital) }}"
                                      class="d-inline" onsubmit="return confirm('{{ __('hospitals.confirm_archive') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">{{ __('app.delete') }}</button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">{{ __('hospitals.hospitals') }}...</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $hospitals->links() }}
    </div>
</div>
@endsection
