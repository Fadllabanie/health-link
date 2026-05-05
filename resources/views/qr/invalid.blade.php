<!doctype html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ __('patients.qr_invalid') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <style>
        body { font-family: 'Cairo', sans-serif; background: #f5f5f9; }
    </style>
</head>
<body>
<div class="container" style="max-width: 480px; margin: 80px auto;">
    <div class="card text-center">
        <div class="card-body py-5">
            <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
            <h5 class="mt-3">
                @if($reason === 'expired')
                    {{ __('patients.qr_expired') }}
                @else
                    {{ __('patients.qr_not_found') }}
                @endif
            </h5>
            <p class="text-muted">{{ __('patients.qr_invalid_desc') }}</p>
        </div>
    </div>
</div>
</body>
</html>
