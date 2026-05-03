@extends('layouts.app')

@section('title', __('doctors.doctor_details'))

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('hospital-admin.doctors.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i>
                    </a>
                    <h5 class="mb-0">{{ $doctor->name }}</h5>
                    @if($doctor->status->value === 'active')
                        <span class="badge bg-label-success">{{ __('doctors.active') }}</span>
                    @elseif($doctor->status->value === 'inactive')
                        <span class="badge bg-label-danger">{{ __('doctors.inactive') }}</span>
                    @else
                        <span class="badge bg-label-warning">{{ __('doctors.on_leave') }}</span>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('hospital-admin.doctors.edit', $doctor) }}" class="btn btn-sm btn-primary">
                        <i class="bx bx-edit me-1"></i>{{ __('app.edit') }}
                    </a>
                    <form method="POST" action="{{ route('hospital-admin.doctors.toggle-status', $doctor) }}" class="d-inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-outline-warning">
                            {{ $doctor->status->value === 'active' ? __('doctors.disable_doctor') : __('doctors.enable_doctor') }}
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">{{ __('app.email') }}</label>
                        <p class="mb-0">{{ $doctor->user->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">{{ __('app.phone') }}</label>
                        <p class="mb-0">{{ $doctor->user->phone ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">{{ __('doctors.license_number') }}</label>
                        <p class="mb-0">{{ $doctor->license_number }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">{{ __('doctors.license_expires_at') }}</label>
                        <p class="mb-0">{{ $doctor->license_expires_at?->format('Y-m-d') ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">{{ __('doctors.primary_specialty') }}</label>
                        <p class="mb-0">{{ $doctor->primarySpecialty?->name ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">{{ __('doctors.department') }}</label>
                        <p class="mb-0">{{ $doctor->department?->name ?? '—' }}</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small">{{ __('doctors.secondary_specialties') }}</label>
                        <div class="d-flex flex-wrap gap-1">
                            @forelse($doctor->specialties as $s)
                                <span class="badge bg-label-primary">{{ $s->name }}</span>
                            @empty
                                <span class="text-muted">—</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">{{ __('doctors.consultation_fee') }}</label>
                        <p class="mb-0">{{ $doctor->consultation_fee ? number_format($doctor->consultation_fee, 2) . ' ' . __('app.currency') : '—' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">{{ __('doctors.years_of_experience') }}</label>
                        <p class="mb-0">{{ $doctor->years_of_experience ?? '—' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">{{ __('doctors.joined_at') }}</label>
                        <p class="mb-0">{{ $doctor->joined_at?->format('Y-m-d') ?? '—' }}</p>
                    </div>
                    @if($doctor->qualifications)
                        <div class="col-12">
                            <label class="form-label text-muted small">{{ __('doctors.qualifications') }}</label>
                            <p class="mb-0">{{ $doctor->qualifications }}</p>
                        </div>
                    @endif
                    @if($doctor->bio)
                        <div class="col-12">
                            <label class="form-label text-muted small">{{ __('doctors.bio') }}</label>
                            <p class="mb-0">{{ $doctor->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">{{ __('doctors.schedules') }}</h6>
                <a href="{{ route('hospital-admin.doctors.schedules.edit', $doctor) }}" class="btn btn-sm btn-outline-primary">
                    {{ __('doctors.manage_schedules') }}
                </a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @php
                        $days = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
                    @endphp
                    @foreach($doctor->schedules->where('is_active', true)->sortBy('day_of_week') as $schedule)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ __('doctors.day_' . $days[$schedule->day_of_week]) }}</span>
                            <span class="text-muted small">{{ $schedule->start_time }} — {{ $schedule->end_time }}</span>
                        </li>
                    @endforeach
                    @if($doctor->schedules->where('is_active', true)->isEmpty())
                        <li class="list-group-item text-center text-muted py-3">{{ __('app.no_data') }}</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
