@extends('layouts.app')

@section('title', $hospital->name)

@section('content')
<div class="row">
    {{-- Hospital Card --}}
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    @if($hospital->logo)
                        <img src="{{ asset('storage/' . $hospital->logo) }}" alt="" class="rounded" width="48" height="48" style="object-fit:cover">
                    @endif
                    <div>
                        <h5 class="mb-0">{{ $hospital->name }}</h5>
                        <small class="text-muted">{{ $hospital->license_number }}</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('super-admin.hospitals.edit', $hospital) }}" class="btn btn-sm btn-outline-primary">
                        {{ __('app.edit') }}
                    </a>
                    <a href="{{ route('super-admin.hospitals.index') }}" class="btn btn-sm btn-outline-secondary">
                        {{ __('app.back') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('hospitals.email') }}</small>
                        <span>{{ $hospital->email }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('hospitals.phone') }}</small>
                        <span>{{ $hospital->phone }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('app.status') }}</small>
                        @php $statusColor = match($hospital->status->value) {
                            'active' => 'success', 'inactive' => 'secondary', 'suspended' => 'danger', default => 'secondary'
                        }; @endphp
                        <span class="badge bg-{{ $statusColor }}">{{ __('app.' . $hospital->status->value) }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('hospitals.country') }} / {{ __('hospitals.city') }}</small>
                        <span>{{ $hospital->country?->name }} / {{ $hospital->city?->name }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('hospitals.subscription_plan') }}</small>
                        <span>{{ __('hospitals.plan_' . $hospital->subscription_plan->value) }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ __('hospitals.bed_capacity') }}</small>
                        <span>{{ $hospital->bed_capacity ?? '—' }}</span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('hospitals.address') }}</small>
                        <span>{{ $hospital->address }}</span>
                    </div>
                    @if($hospital->specialties->isNotEmpty())
                    <div class="col-12">
                        <small class="text-muted d-block mb-1">{{ __('hospitals.specialties') }}</small>
                        @foreach($hospital->specialties as $spec)
                            <span class="badge bg-label-primary me-1">{{ $spec->name }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- Status Change --}}
            <div class="card-footer d-flex gap-2 flex-wrap">
                @foreach(['active','inactive','suspended'] as $statusOption)
                    @if($hospital->status->value !== $statusOption)
                        <form method="POST" action="{{ route('super-admin.hospitals.update-status', $hospital) }}"
                              onsubmit="return confirm('{{ __('hospitals.confirm_status_change') }}')">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $statusOption }}">
                            @php $btnColor = match($statusOption) {
                                'active' => 'success', 'inactive' => 'secondary', 'suspended' => 'danger', default => 'secondary'
                            }; @endphp
                            <button class="btn btn-sm btn-outline-{{ $btnColor }}">
                                {{ __('hospitals.set_' . $statusOption) }}
                            </button>
                        </form>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Departments --}}
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">{{ __('hospitals.departments') }}</h6>
            </div>
            <div class="card-body">
                @forelse($hospital->departments as $dept)
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                        <span>{{ $dept->name }}</span>
                        <span class="badge {{ $dept->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                            {{ $dept->is_active ? __('app.active') : __('app.inactive') }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted mb-0">{{ __('hospitals.no_departments') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Admins --}}
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">{{ __('hospitals.admins') }}</h6>
                <a href="{{ route('super-admin.hospitals.admins.create', $hospital) }}" class="btn btn-sm btn-outline-primary">
                    {{ __('hospitals.add_admin') }}
                </a>
            </div>
            <div class="card-body">
                @forelse($hospital->admins as $admin)
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                        <div>
                            <span>{{ $admin->first_name }} {{ $admin->last_name }}</span>
                            <small class="text-muted d-block">{{ $admin->email }}</small>
                        </div>
                        <div class="d-flex gap-1">
                            <a href="{{ route('super-admin.hospitals.admins.edit', [$hospital, $admin]) }}"
                               class="btn btn-xs btn-outline-secondary">{{ __('app.edit') }}</a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">{{ __('hospitals.no_admins') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
