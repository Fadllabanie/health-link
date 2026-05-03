<x-guest-layout>
@section('title', __('auth.login'))
<div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6">
        <div class="card p-sm-7 p-2">
            <div class="card-body mt-1">
                <h4 class="mb-1 fw-semibold">{{ __('app.name') }} 👋</h4>
                <p class="mb-5 text-muted">{{ __('auth.login_subtitle') }}</p>

                @if(session('status'))
                    <div class="alert alert-success mb-4">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mb-5">
                    @csrf

                    <div class="form-floating form-floating-outline mb-5 @error('email') form-control-validation @enderror">
                        <input id="email" type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="{{ __('auth.email') }}"
                               value="{{ old('email') }}" required autofocus autocomplete="username" />
                        <label for="email">{{ __('auth.email') }}</label>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <div class="form-password-toggle @error('password') form-control-validation @enderror">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input id="password" type="password" name="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="{{ __('auth.password_label') }}"
                                           required autocomplete="current-password" />
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
                    </div>

                    <div class="d-flex justify-content-between mb-5">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember_me" name="remember" />
                            <label class="form-check-label" for="remember_me">{{ __('auth.remember_me') }}</label>
                        </div>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="float-end mb-1 text-primary">
                                {{ __('auth.forgot_password') }}
                            </a>
                        @endif
                    </div>

                    <button class="btn btn-primary d-grid w-100" type="submit">
                        {{ __('auth.login') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-guest-layout>
