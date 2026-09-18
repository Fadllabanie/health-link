@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content')
<div class="card mb-4">
    <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <div class="fw-semibold">{{ now()->translatedFormat('l، j F Y') }}</div>
            <div class="text-muted small">{{ now()->translatedFormat('h:i A') }}</div>
        </div>

        @if($weather)
            <div class="d-flex align-items-center gap-2">
                <img src="https://openweathermap.org/img/wn/{{ $weather['icon'] }}@2x.png" alt="" width="48" height="48">
                <div>
                    <div class="fw-semibold">{{ $weather['temp'] }}°C</div>
                    <div class="text-muted small">{{ $weather['description'] }} — {{ $weather['city'] }}</div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-body">
        {{ __("You're logged in!") }}
    </div>
</div>
@endsection
