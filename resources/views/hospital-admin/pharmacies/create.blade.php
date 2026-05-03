@extends('layouts.app')

@section('title', __('pharmacies.add_pharmacy'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <a href="{{ route('hospital-admin.pharmacies.index') }}" class="btn btn-sm btn-outline-secondary me-3">
                    <i class="bx bx-arrow-back"></i>
                </a>
                <h5 class="mb-0">{{ __('pharmacies.add_pharmacy') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hospital-admin.pharmacies.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('pharmacies.pharmacy_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('pharmacies.license_number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror"
                                value="{{ old('license_number') }}" required>
                            @error('license_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('pharmacies.email') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('pharmacies.phone') }} <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.country') }} <span class="text-danger">*</span></label>
                            <select name="country_id" class="form-select @error('country_id') is-invalid @enderror" required>
                                <option value="">-- {{ __('app.select') }} --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('country_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.city') }} <span class="text-danger">*</span></label>
                            <select name="city_id" class="form-select @error('city_id') is-invalid @enderror" required>
                                <option value="">-- {{ __('app.select') }} --</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" @selected(old('city_id') == $city->id)>{{ $city->name }}</option>
                                @endforeach
                            </select>
                            @error('city_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('pharmacies.address') }} <span class="text-danger">*</span></label>
                            <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('pharmacies.type') }} <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">-- {{ __('app.select') }} --</option>
                                <option value="in_hospital" @selected(old('type') === 'in_hospital')>{{ __('pharmacies.pharmacy_type_in_hospital') }}</option>
                                <option value="external" @selected(old('type') === 'external')>{{ __('pharmacies.pharmacy_type_external') }}</option>
                                <option value="chain" @selected(old('type') === 'chain')>{{ __('pharmacies.pharmacy_type_chain') }}</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('pharmacies.status') }} <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" @selected(old('status', 'active') === 'active')>{{ __('pharmacies.status_active') }}</option>
                                <option value="inactive" @selected(old('status') === 'inactive')>{{ __('pharmacies.status_inactive') }}</option>
                                <option value="suspended" @selected(old('status') === 'suspended')>{{ __('pharmacies.status_suspended') }}</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="is_24_hours" id="is_24_hours" value="1"
                                    @checked(old('is_24_hours'))>
                                <label class="form-check-label" for="is_24_hours">{{ __('pharmacies.is_24_hours') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('pharmacies.opening_time') }}</label>
                            <input type="time" name="opening_time" class="form-control @error('opening_time') is-invalid @enderror"
                                value="{{ old('opening_time') }}">
                            @error('opening_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('pharmacies.closing_time') }}</label>
                            <input type="time" name="closing_time" class="form-control @error('closing_time') is-invalid @enderror"
                                value="{{ old('closing_time') }}">
                            @error('closing_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.logo') }}</label>
                            <input type="file" name="logo" accept="image/*" class="form-control @error('logo') is-invalid @enderror">
                            @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                        <a href="{{ route('hospital-admin.pharmacies.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
