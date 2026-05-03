@extends('layouts.app')

@section('title', __('hospitals.add_admin'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('hospitals.add_admin') }} — {{ $hospital->name }}</h5>
                <a href="{{ route('super-admin.hospitals.admins.index', $hospital) }}" class="btn btn-outline-secondary btn-sm">
                    {{ __('app.back') }}
                </a>
            </div>
            <form method="POST" action="{{ route('super-admin.hospitals.admins.store', $hospital) }}">
                @csrf
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('hospitals.admin_first_name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                            class="form-control @error('first_name') is-invalid @enderror">
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('hospitals.admin_last_name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                            class="form-control @error('last_name') is-invalid @enderror">
                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('hospitals.admin_email') }} <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('hospitals.admin_phone') }}</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('hospitals.admin_password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="card-footer d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                    <a href="{{ route('super-admin.hospitals.admins.index', $hospital) }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
