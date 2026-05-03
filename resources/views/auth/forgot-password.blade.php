<x-guest-layout>
@section('title', __('auth.forgot_password'))
<div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6">
        <div class="card p-sm-7 p-2">
            <div class="card-body mt-1">
                <h4 class="mb-1 fw-semibold">{{ __('auth.forgot_password') }} 🔒</h4>
                <p class="mb-5 text-muted">{{ __('auth.forgot_password_hint') }}</p>

                @if(session('status'))
                    <div class="alert alert-success mb-4">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="mb-5">
                    @csrf

                    <div class="form-floating form-floating-outline mb-5 @error('email') form-control-validation @enderror">
                        <input id="email" type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="{{ __('auth.email') }}"
                               value="{{ old('email') }}" required autofocus />
                        <label for="email">{{ __('auth.email') }}</label>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button class="btn btn-primary d-grid w-100" type="submit">
                        {{ __('auth.send_reset_link') }}
                    </button>
                </form>

                <p class="text-center mt-2">
                    <a href="{{ route('login') }}">
                        <i class="icon-base ri ri-arrow-right-line me-1"></i>
                        {{ __('auth.back_to_login') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
</x-guest-layout>
