<x-guest-layout>
@section('title', __('auth.reset_password'))
<div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6">
        <div class="card p-sm-7 p-2">
            <div class="card-body mt-1">
                <h4 class="mb-1 fw-semibold">{{ __('auth.reset_password') }} 🔑</h4>
                <p class="mb-5 text-muted">{{ __('auth.reset_password_hint') }}</p>

                <form method="POST" action="{{ route('password.store') }}" class="mb-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}" />

                    <div class="form-floating form-floating-outline mb-5 @error('email') form-control-validation @enderror">
                        <input id="email" type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="{{ __('auth.email') }}"
                               value="{{ old('email', $request->email) }}" required />
                        <label for="email">{{ __('auth.email') }}</label>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-5 form-password-toggle @error('password') form-control-validation @enderror">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <input id="password" type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="{{ __('auth.password_label') }}" required />
                                <label for="password">{{ __('auth.password_label') }}</label>
                            </div>
                            <span class="input-group-text cursor-pointer">
                                <i class="icon-base ri ri-eye-off-line"></i>
                            </span>
                        </div>
                        @error('password')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-5 form-password-toggle">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                       class="form-control"
                                       placeholder="{{ __('auth.confirm_password') }}" required />
                                <label for="password_confirmation">{{ __('auth.confirm_password') }}</label>
                            </div>
                            <span class="input-group-text cursor-pointer">
                                <i class="icon-base ri ri-eye-off-line"></i>
                            </span>
                        </div>
                    </div>

                    <button class="btn btn-primary d-grid w-100" type="submit">
                        {{ __('auth.reset_password') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-guest-layout>
