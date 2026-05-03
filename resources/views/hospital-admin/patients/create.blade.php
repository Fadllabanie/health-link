@extends('layouts.app')

@section('title', __('patients.add_patient'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <a href="{{ route('hospital-admin.patients.index') }}" class="btn btn-sm btn-outline-secondary me-3">
                    <i class="bx bx-arrow-back"></i>
                </a>
                <h5 class="mb-0">{{ __('patients.add_patient') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hospital-admin.patients.store') }}">
                    @csrf

                    {{-- Personal Info --}}
                    <h6 class="fw-semibold text-primary mb-3">{{ __('patients.personal_info') }}</h6>
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
                            <label class="form-label">{{ __('app.phone') }}</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('app.date_of_birth') }}</label>
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                                value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('app.gender') }}</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">-- {{ __('app.select') }} --</option>
                                <option value="male" @selected(old('gender') === 'male')>{{ __('app.male') }}</option>
                                <option value="female" @selected(old('gender') === 'female')>{{ __('app.female') }}</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('app.national_id') }}</label>
                            <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror"
                                value="{{ old('national_id') }}">
                            @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.city') }}</label>
                            <select name="city_id" class="form-select @error('city_id') is-invalid @enderror">
                                <option value="">-- {{ __('app.select') }} --</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" @selected(old('city_id') == $city->id)>{{ $city->name }}</option>
                                @endforeach
                            </select>
                            @error('city_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('patients.marital_status') }}</label>
                            <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                                <option value="">-- {{ __('app.select') }} --</option>
                                <option value="single" @selected(old('marital_status') === 'single')>{{ __('patients.single') }}</option>
                                <option value="married" @selected(old('marital_status') === 'married')>{{ __('patients.married') }}</option>
                                <option value="divorced" @selected(old('marital_status') === 'divorced')>{{ __('patients.divorced') }}</option>
                                <option value="widowed" @selected(old('marital_status') === 'widowed')>{{ __('patients.widowed') }}</option>
                            </select>
                            @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('patients.occupation') }}</label>
                            <input type="text" name="occupation" class="form-control @error('occupation') is-invalid @enderror"
                                value="{{ old('occupation') }}">
                            @error('occupation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr>

                    {{-- Medical Info --}}
                    <h6 class="fw-semibold text-primary mb-3">{{ __('patients.medical_info') }}</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('patients.blood_type') }}</label>
                            <select name="blood_type" class="form-select @error('blood_type') is-invalid @enderror">
                                <option value="">-- {{ __('app.select') }} --</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                                    <option value="{{ $bt }}" @selected(old('blood_type') === $bt)>{{ $bt }}</option>
                                @endforeach
                            </select>
                            @error('blood_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('patients.height_cm') }}</label>
                            <input type="number" name="height_cm" step="0.01" min="50" max="250"
                                class="form-control @error('height_cm') is-invalid @enderror"
                                value="{{ old('height_cm') }}">
                            @error('height_cm')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('patients.weight_kg') }}</label>
                            <input type="number" name="weight_kg" step="0.01" min="10" max="300"
                                class="form-control @error('weight_kg') is-invalid @enderror"
                                value="{{ old('weight_kg') }}">
                            @error('weight_kg')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('patients.allergies') }}</label>
                            <textarea name="allergies" rows="2"
                                class="form-control @error('allergies') is-invalid @enderror">{{ old('allergies') }}</textarea>
                            @error('allergies')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('patients.chronic_conditions') }}</label>
                            <textarea name="chronic_conditions" rows="2"
                                class="form-control @error('chronic_conditions') is-invalid @enderror">{{ old('chronic_conditions') }}</textarea>
                            @error('chronic_conditions')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('patients.current_medications') }}</label>
                            <textarea name="current_medications" rows="2"
                                class="form-control @error('current_medications') is-invalid @enderror">{{ old('current_medications') }}</textarea>
                            @error('current_medications')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr>

                    {{-- Emergency Contact --}}
                    <h6 class="fw-semibold text-primary mb-3">{{ __('patients.emergency_contact') }}</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('patients.emergency_contact_name') }}</label>
                            <input type="text" name="emergency_contact_name"
                                class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                value="{{ old('emergency_contact_name') }}">
                            @error('emergency_contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('patients.emergency_contact_phone') }}</label>
                            <input type="text" name="emergency_contact_phone"
                                class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                value="{{ old('emergency_contact_phone') }}">
                            @error('emergency_contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('patients.emergency_contact_relation') }}</label>
                            <input type="text" name="emergency_contact_relation"
                                class="form-control @error('emergency_contact_relation') is-invalid @enderror"
                                value="{{ old('emergency_contact_relation') }}">
                            @error('emergency_contact_relation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr>

                    {{-- Insurance --}}
                    <h6 class="fw-semibold text-primary mb-3">{{ __('patients.insurance_info') }}</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('patients.insurance_provider') }}</label>
                            <input type="text" name="insurance_provider"
                                class="form-control @error('insurance_provider') is-invalid @enderror"
                                value="{{ old('insurance_provider') }}">
                            @error('insurance_provider')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('patients.insurance_policy_number') }}</label>
                            <input type="text" name="insurance_policy_number"
                                class="form-control @error('insurance_policy_number') is-invalid @enderror"
                                value="{{ old('insurance_policy_number') }}">
                            @error('insurance_policy_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                        <a href="{{ route('hospital-admin.patients.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
