@extends('layouts.app')

@section('title', __('hospitals.add_hospital'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('hospitals.add_hospital') }}</h5>
                <a href="{{ route('super-admin.hospitals.index') }}" class="btn btn-outline-secondary btn-sm">
                    {{ __('app.back') }}
                </a>
            </div>

            <form method="POST" action="{{ route('super-admin.hospitals.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                    {{-- Hospital Info --}}
                    <h6 class="fw-semibold mb-3">{{ __('hospitals.hospital_details') }}</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('hospitals.name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('hospitals.license_number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="license_number" value="{{ old('license_number') }}"
                                class="form-control @error('license_number') is-invalid @enderror">
                            @error('license_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('hospitals.email') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('hospitals.phone') }} <span class="text-danger">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('hospitals.alternate_phone') }}</label>
                            <input type="text" name="alternate_phone" value="{{ old('alternate_phone') }}"
                                class="form-control @error('alternate_phone') is-invalid @enderror">
                            @error('alternate_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('hospitals.country') }} <span class="text-danger">*</span></label>
                            <select name="country_id" class="form-select @error('country_id') is-invalid @enderror">
                                <option value="">-- {{ __('hospitals.country') }} --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('hospitals.city') }} <span class="text-danger">*</span></label>
                            <select name="city_id" class="form-select @error('city_id') is-invalid @enderror">
                                <option value="">-- {{ __('hospitals.city') }} --</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" @selected(old('city_id') == $city->id)>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('hospitals.subscription_plan') }}</label>
                            <select name="subscription_plan" class="form-select">
                                @foreach(['free','basic','premium','enterprise'] as $plan)
                                    <option value="{{ $plan }}" @selected(old('subscription_plan', 'basic') === $plan)>
                                        {{ __('hospitals.plan_' . $plan) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('hospitals.address') }} <span class="text-danger">*</span></label>
                            <textarea name="address" rows="2"
                                class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('hospitals.website') }}</label>
                            <input type="url" name="website" value="{{ old('website') }}"
                                class="form-control @error('website') is-invalid @enderror">
                            @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('hospitals.bed_capacity') }}</label>
                            <input type="number" name="bed_capacity" value="{{ old('bed_capacity') }}" min="1"
                                class="form-control @error('bed_capacity') is-invalid @enderror">
                            @error('bed_capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('hospitals.logo') }}</label>
                            <input type="file" name="logo" accept="image/*"
                                class="form-control @error('logo') is-invalid @enderror">
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('hospitals.specialties') }}</label>
                            <div class="row g-2">
                                @foreach($specialties as $specialty)
                                    <div class="col-md-3 col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                name="specialty_ids[]" value="{{ $specialty->id }}"
                                                id="spec_{{ $specialty->id }}"
                                                @checked(in_array($specialty->id, old('specialty_ids', [])))>
                                            <label class="form-check-label" for="spec_{{ $specialty->id }}">
                                                {{ $specialty->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- First Admin --}}
                    <h6 class="fw-semibold mb-3">{{ __('hospitals.first_admin') }}</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('hospitals.admin_first_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="admin_first_name" value="{{ old('admin_first_name') }}"
                                class="form-control @error('admin_first_name') is-invalid @enderror">
                            @error('admin_first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('hospitals.admin_last_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="admin_last_name" value="{{ old('admin_last_name') }}"
                                class="form-control @error('admin_last_name') is-invalid @enderror">
                            @error('admin_last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('hospitals.admin_email') }} <span class="text-danger">*</span></label>
                            <input type="email" name="admin_email" value="{{ old('admin_email') }}"
                                class="form-control @error('admin_email') is-invalid @enderror">
                            @error('admin_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('hospitals.admin_phone') }}</label>
                            <input type="text" name="admin_phone" value="{{ old('admin_phone') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('hospitals.admin_password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="admin_password"
                                class="form-control @error('admin_password') is-invalid @enderror">
                            @error('admin_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                    <a href="{{ route('super-admin.hospitals.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
