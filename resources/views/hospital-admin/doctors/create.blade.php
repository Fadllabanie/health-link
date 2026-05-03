@extends('layouts.app')

@section('title', __('doctors.add_doctor'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <a href="{{ route('hospital-admin.doctors.index') }}" class="btn btn-sm btn-outline-secondary me-3">
                    <i class="bx bx-arrow-back"></i>
                </a>
                <h5 class="mb-0">{{ __('doctors.add_doctor') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hospital-admin.doctors.store') }}">
                    @csrf

                    <h6 class="fw-semibold text-primary mb-3">{{ __('doctors.personal_info') }}</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.first_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                value="{{ old('first_name') }}" required>
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.last_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name') }}" required>
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.email') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.phone') }} <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-semibold text-primary mb-3">{{ __('doctors.professional_info') }}</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('doctors.license_number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror"
                                value="{{ old('license_number') }}" required>
                            @error('license_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('doctors.license_expires_at') }}</label>
                            <input type="date" name="license_expires_at" class="form-control @error('license_expires_at') is-invalid @enderror"
                                value="{{ old('license_expires_at') }}">
                            @error('license_expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('doctors.primary_specialty') }} <span class="text-danger">*</span></label>
                            <select name="primary_specialty_id" class="form-select @error('primary_specialty_id') is-invalid @enderror" required>
                                <option value="">-- {{ __('app.select') }} --</option>
                                @foreach($specialties as $s)
                                    <option value="{{ $s->id }}" @selected(old('primary_specialty_id') == $s->id)>{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('primary_specialty_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('doctors.department') }} <span class="text-danger">*</span></label>
                            <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                <option value="">-- {{ __('app.select') }} --</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}" @selected(old('department_id') == $d->id)>{{ $d->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('doctors.secondary_specialties') }}</label>
                            <select name="secondary_specialties[]" class="form-select" multiple>
                                @foreach($specialties as $s)
                                    <option value="{{ $s->id }}"
                                        @selected(in_array($s->id, old('secondary_specialties', [])))>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">{{ __('app.hold_ctrl_to_select_multiple') }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('doctors.consultation_fee') }}</label>
                            <input type="number" name="consultation_fee" step="0.01" min="0"
                                class="form-control @error('consultation_fee') is-invalid @enderror"
                                value="{{ old('consultation_fee') }}">
                            @error('consultation_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('doctors.years_of_experience') }}</label>
                            <input type="number" name="years_of_experience" min="0" max="60"
                                class="form-control @error('years_of_experience') is-invalid @enderror"
                                value="{{ old('years_of_experience') }}">
                            @error('years_of_experience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('doctors.joined_at') }}</label>
                            <input type="date" name="joined_at" class="form-control @error('joined_at') is-invalid @enderror"
                                value="{{ old('joined_at', now()->toDateString()) }}">
                            @error('joined_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('doctors.qualifications') }}</label>
                            <textarea name="qualifications" rows="3"
                                class="form-control @error('qualifications') is-invalid @enderror">{{ old('qualifications') }}</textarea>
                            @error('qualifications')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('doctors.bio') }}</label>
                            <textarea name="bio" rows="3"
                                class="form-control @error('bio') is-invalid @enderror">{{ old('bio') }}</textarea>
                            @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                        <a href="{{ route('hospital-admin.doctors.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
