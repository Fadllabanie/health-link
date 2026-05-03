@extends('layouts.app')

@section('title', __('doctors.edit_doctor'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <a href="{{ route('hospital-admin.doctors.show', $doctor) }}" class="btn btn-sm btn-outline-secondary me-3">
                    <i class="bx bx-arrow-back"></i>
                </a>
                <h5 class="mb-0">{{ __('doctors.edit_doctor') }}: {{ $doctor->name }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hospital-admin.doctors.update', $doctor) }}">
                    @csrf @method('PUT')

                    <h6 class="fw-semibold text-primary mb-3">{{ __('doctors.personal_info') }}</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.first_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                value="{{ old('first_name', $doctor->user->first_name) }}" required>
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.last_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name', $doctor->user->last_name) }}" required>
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.email') }}</label>
                            <input type="email" class="form-control" value="{{ $doctor->user->email }}" disabled>
                            <div class="form-text">{{ __('app.email_cannot_be_changed') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.phone') }} <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $doctor->user->phone) }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-semibold text-primary mb-3">{{ __('doctors.professional_info') }}</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('doctors.license_number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror"
                                value="{{ old('license_number', $doctor->license_number) }}" required>
                            @error('license_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('doctors.license_expires_at') }}</label>
                            <input type="date" name="license_expires_at" class="form-control @error('license_expires_at') is-invalid @enderror"
                                value="{{ old('license_expires_at', $doctor->license_expires_at?->toDateString()) }}">
                            @error('license_expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('doctors.primary_specialty') }} <span class="text-danger">*</span></label>
                            <select name="primary_specialty_id" class="form-select @error('primary_specialty_id') is-invalid @enderror" required>
                                <option value="">-- {{ __('app.select') }} --</option>
                                @foreach($specialties as $s)
                                    <option value="{{ $s->id }}"
                                        @selected(old('primary_specialty_id', $doctor->primary_specialty_id) == $s->id)>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('primary_specialty_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('doctors.department') }} <span class="text-danger">*</span></label>
                            <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                <option value="">-- {{ __('app.select') }} --</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}"
                                        @selected(old('department_id', $doctor->department_id) == $d->id)>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('doctors.secondary_specialties') }}</label>
                            <select name="secondary_specialties[]" class="form-select" multiple>
                                @php $selectedSecondary = old('secondary_specialties', $doctor->specialties->pluck('id')->toArray()); @endphp
                                @foreach($specialties as $s)
                                    <option value="{{ $s->id }}" @selected(in_array($s->id, $selectedSecondary))>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('doctors.consultation_fee') }}</label>
                            <input type="number" name="consultation_fee" step="0.01" min="0"
                                class="form-control" value="{{ old('consultation_fee', $doctor->consultation_fee) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('doctors.years_of_experience') }}</label>
                            <input type="number" name="years_of_experience" min="0" max="60"
                                class="form-control" value="{{ old('years_of_experience', $doctor->years_of_experience) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('doctors.joined_at') }}</label>
                            <input type="date" name="joined_at" class="form-control"
                                value="{{ old('joined_at', $doctor->joined_at?->toDateString()) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('doctors.qualifications') }}</label>
                            <textarea name="qualifications" rows="3" class="form-control">{{ old('qualifications', $doctor->qualifications) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('doctors.bio') }}</label>
                            <textarea name="bio" rows="3" class="form-control">{{ old('bio', $doctor->bio) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                        <a href="{{ route('hospital-admin.doctors.show', $doctor) }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
