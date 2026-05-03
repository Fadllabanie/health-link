@extends('layouts.app')

@section('title', __('doctors.manage_schedules'))

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center gap-2">
        <a href="{{ route('hospital-admin.doctors.show', $doctor) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('doctors.manage_schedules') }}: {{ $doctor->name }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('hospital-admin.doctors.schedules.update', $doctor) }}">
            @csrf @method('PUT')

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('app.day') }}</th>
                            <th>{{ __('doctors.start_time') }}</th>
                            <th>{{ __('doctors.end_time') }}</th>
                            <th>{{ __('doctors.slot_duration') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $dayKeys = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
                        @endphp
                        @foreach($dayKeys as $i => $day)
                            @php $existing = $schedules->get($i); @endphp
                            <tr>
                                <td class="fw-medium">{{ __('doctors.day_' . $day) }}</td>
                                <td>
                                    <input type="time" name="schedules[{{ $i }}][start_time]"
                                        class="form-control form-control-sm @error('schedules.'.$i.'.start_time') is-invalid @enderror"
                                        value="{{ old("schedules.{$i}.start_time", $existing?->start_time) }}">
                                    @error('schedules.'.$i.'.start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="time" name="schedules[{{ $i }}][end_time]"
                                        class="form-control form-control-sm @error('schedules.'.$i.'.end_time') is-invalid @enderror"
                                        value="{{ old("schedules.{$i}.end_time", $existing?->end_time) }}">
                                    @error('schedules.'.$i.'.end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="number" name="schedules[{{ $i }}][slot_duration_minutes]"
                                        class="form-control form-control-sm" min="5" max="120"
                                        value="{{ old("schedules.{$i}.slot_duration_minutes", $existing?->slot_duration_minutes ?? 30) }}"
                                        style="width: 90px">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="form-text mb-3">{{ __('app.leave_empty_to_disable_day') }}</div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                <a href="{{ route('hospital-admin.doctors.show', $doctor) }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
