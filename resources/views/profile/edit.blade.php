@extends('layouts.app')

@section('title', __('app.my_profile'))

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('app.my_profile') }}</li>
@endsection

@section('content')
@php
    $avatarUrl = $user->avatar
        ? asset('storage/'.$user->avatar)
        : asset('assets/img/avatars/1.png');
@endphp

<div class="row">
    <div class="col-12 col-lg-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $avatarUrl }}" alt="avatar"
                     class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover">
                <h5 class="mb-1">{{ $user->full_name }}</h5>
                <small class="text-muted d-block mb-2">{{ $user->email }}</small>
                @foreach($user->roles as $role)
                    <span class="badge bg-label-primary">{{ __('app.roles.'.$role->name) }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card mb-4">
            <h5 class="card-header">{{ __('app.profile_information') }}</h5>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="first_name">{{ __('app.first_name') }} <span class="text-danger">*</span></label>
                            <input type="text" id="first_name" name="first_name"
                                   class="form-control @error('first_name') is-invalid @enderror"
                                   value="{{ old('first_name', $user->first_name) }}" required>
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="last_name">{{ __('app.last_name') }} <span class="text-danger">*</span></label>
                            <input type="text" id="last_name" name="last_name"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="email">{{ __('app.email') }} <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="phone">{{ __('app.phone') }}</label>
                            <input type="text" id="phone" name="phone" dir="ltr"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="gender">{{ __('app.gender') }}</label>
                            <select id="gender" name="gender"
                                    class="form-select @error('gender') is-invalid @enderror">
                                <option value="">—</option>
                                @foreach(\App\Enums\UserGender::cases() as $g)
                                    <option value="{{ $g->value }}" @selected(old('gender', $user->gender?->value) === $g->value)>
                                        {{ __('app.'.$g->value) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="date_of_birth">{{ __('app.date_of_birth') }}</label>
                            <input type="date" id="date_of_birth" name="date_of_birth"
                                   class="form-control @error('date_of_birth') is-invalid @enderror"
                                   value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="national_id">{{ __('app.national_id') }}</label>
                            <input type="text" id="national_id" name="national_id" dir="ltr"
                                   class="form-control @error('national_id') is-invalid @enderror"
                                   value="{{ old('national_id', $user->national_id) }}">
                            @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="avatar">{{ __('app.avatar') }}</label>
                            <input type="file" id="avatar" name="avatar" accept="image/*"
                                   class="form-control @error('avatar') is-invalid @enderror">
                            @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="address">{{ __('app.address') }}</label>
                            <textarea id="address" name="address" rows="2"
                                      class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <h5 class="card-header">{{ __('app.update_password') }}</h5>
            <div class="card-body">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label" for="current_password">{{ __('app.current_password') }}</label>
                            <input type="password" id="current_password" name="current_password"
                                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror">
                            @error('current_password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="password">{{ __('app.new_password') }}</label>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password', 'updatePassword') is-invalid @enderror">
                            @error('password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="password_confirmation">{{ __('app.confirm_password') }}</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-control">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">{{ __('app.update_password') }}</button>
                        @if (session('status') === 'password-updated')
                            <span class="text-success ms-2">{{ __('app.password_updated') }}</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
