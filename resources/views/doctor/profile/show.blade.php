@extends('layouts.app')

@section('title', __('doctors.doctor_details'))

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('doctors.personal_info') }}</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('app.name') }}</small>
                        <strong>{{ $doctor->user->full_name }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('app.email') }}</small>
                        {{ $doctor->user->email }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('app.phone') }}</small>
                        {{ $doctor->user->phone ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('app.gender') }}</small>
                        {{ $doctor->user->gender ? __('app.'.$doctor->user->gender) : '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('doctors.joined_at') }}</small>
                        {{ $doctor->joined_at?->format('Y-m-d') ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('doctors.professional_info') }}</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('doctors.primary_specialty') }}</small>
                        {{ $doctor->primarySpecialty?->name ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('doctors.department') }}</small>
                        {{ $doctor->department?->name ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('doctors.license_number') }}</small>
                        {{ $doctor->license_number ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('doctors.license_expires_at') }}</small>
                        {{ $doctor->license_expires_at?->format('Y-m-d') ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('doctors.years_of_experience') }}</small>
                        {{ $doctor->years_of_experience ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('doctors.consultation_fee') }}</small>
                        {{ $doctor->consultation_fee ? number_format($doctor->consultation_fee, 2).' ر.س' : '—' }}
                    </div>
                    @if($doctor->specialties->count())
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('doctors.secondary_specialties') }}</small>
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            @foreach($doctor->specialties as $specialty)
                                <span class="badge bg-label-primary">{{ $specialty->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($doctor->qualifications)
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('doctors.qualifications') }}</small>
                        {{ $doctor->qualifications }}
                    </div>
                    @endif
                    @if($doctor->bio)
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('doctors.bio') }}</small>
                        {{ $doctor->bio }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('app.hospital') }}</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('hospitals.name') }}</small>
                        <strong>{{ $doctor->hospital->name ?? '—' }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('app.city') }}</small>
                        {{ $doctor->hospital->city?->name ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ __('app.phone') }}</small>
                        {{ $doctor->hospital->phone ?? '—' }}
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">{{ __('app.address') }}</small>
                        {{ $doctor->hospital->address ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">{{ __('doctors.schedules') }}</h6></div>
            <div class="card-body p-0">
                @if($doctor->schedules->isEmpty())
                    <p class="text-muted p-3 mb-0">{{ __('doctors.no_schedule') }}</p>
                @else
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('app.day') }}</th>
                                <th>{{ __('doctors.start_time') }}</th>
                                <th>{{ __('doctors.end_time') }}</th>
                                <th>{{ __('doctors.slot_duration') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctor->schedules as $schedule)
                                <tr>
                                    <td>{{ __('doctors.day_'.$schedule->day_of_week) }}</td>
                                    <td>{{ $schedule->start_time }}</td>
                                    <td>{{ $schedule->end_time }}</td>
                                    <td>{{ $schedule->slot_duration_minutes }} {{ __('app.minutes') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
