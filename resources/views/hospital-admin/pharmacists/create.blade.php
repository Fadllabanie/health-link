@extends('layouts.app')

@section('title', __('pharmacies.add_pharmacist'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <a href="{{ route('hospital-admin.pharmacies.show', $pharmacy) }}" class="btn btn-sm btn-outline-secondary me-3">
                    <i class="bx bx-arrow-back"></i>
                </a>
                <h5 class="mb-0">{{ __('pharmacies.add_pharmacist') }} — {{ $pharmacy->name }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hospital-admin.pharmacies.pharmacists.store', $pharmacy) }}">
                    @csrf

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
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('pharmacies.license_number') }}</label>
                            <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror"
                                value="{{ old('license_number') }}">
                            @error('license_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('pharmacies.location') }}</label>
                            <input type="text" name="position" class="form-control @error('position') is-invalid @enderror"
                                value="{{ old('position') }}" placeholder="{{ __('app.optional') }}">
                            @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                        <a href="{{ route('hospital-admin.pharmacies.show', $pharmacy) }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
