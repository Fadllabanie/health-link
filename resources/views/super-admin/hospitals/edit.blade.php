@extends('layouts.app')

@section('title', __('hospitals.edit_hospital'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('hospitals.edit_hospital') }}: {{ $hospital->name }}</h5>
                <a href="{{ route('super-admin.hospitals.show', $hospital) }}" class="btn btn-outline-secondary btn-sm">
                    {{ __('app.back') }}
                </a>
            </div>

            <form method="POST" action="{{ route('super-admin.hospitals.update', $hospital) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('hospitals.name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $hospital->name) }}"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('hospitals.license_number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="license_number" value="{{ old('license_number', $hospital->license_number) }}"
                                class="form-control @error('license_number') is-invalid @enderror">
                            @error('license_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('hospitals.email') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $hospital->email) }}"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('hospitals.phone') }} <span class="text-danger">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $hospital->phone) }}"
                                class="form-control @error('phone') is-invalid @enderror">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('hospitals.alternate_phone') }}</label>
                            <input type="text" name="alternate_phone" value="{{ old('alternate_phone', $hospital->alternate_phone) }}"
                                class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('hospitals.country') }} <span class="text-danger">*</span></label>
                            <select name="country_id" class="form-select @error('country_id') is-invalid @enderror">
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @selected(old('country_id', $hospital->country_id) == $country->id)>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('hospitals.city') }} <span class="text-danger">*</span></label>
                            <select name="city_id" class="form-select @error('city_id') is-invalid @enderror">
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" @selected(old('city_id', $hospital->city_id) == $city->id)>
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
                                    <option value="{{ $plan }}" @selected(old('subscription_plan', $hospital->subscription_plan->value) === $plan)>
                                        {{ __('hospitals.plan_' . $plan) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('hospitals.address') }} <span class="text-danger">*</span></label>
                            <textarea name="address" rows="2"
                                class="form-control @error('address') is-invalid @enderror">{{ old('address', $hospital->address) }}</textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('hospitals.website') }}</label>
                            <input type="url" name="website" value="{{ old('website', $hospital->website) }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('hospitals.bed_capacity') }}</label>
                            <input type="number" name="bed_capacity" value="{{ old('bed_capacity', $hospital->bed_capacity) }}" min="1"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('hospitals.logo') }}</label>
                            @if($hospital->logo)
                                <div class="mb-1">
                                    <img src="{{ asset('storage/' . $hospital->logo) }}" height="40" class="rounded">
                                </div>
                            @endif
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
                                                @checked(in_array($specialty->id, old('specialty_ids', $hospital->specialties->pluck('id')->toArray())))>
                                            <label class="form-check-label" for="spec_{{ $specialty->id }}">
                                                {{ $specialty->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                    <a href="{{ route('super-admin.hospitals.show', $hospital) }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
