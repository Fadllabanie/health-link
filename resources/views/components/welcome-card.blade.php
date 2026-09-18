@php
    $__user = auth()->user();
    $__role = $__user->getRoleNames()->first();
@endphp

<div class="card mb-4">
    <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar avatar-lg flex-shrink-0">
                <span class="avatar-initial rounded-circle bg-label-primary">
                    <span class="iconify" data-icon="tabler:user" style="font-size: 1.5rem"></span>
                </span>
            </div>
            <div>
                <h5 class="mb-1">{{ __('app.welcome') }}, {{ $__user->full_name }}</h5>
                <span class="badge bg-label-primary">{{ $__role ? __('app.roles.'.$__role) : '—' }}</span>
            </div>
        </div>

        <div class="text-end">
            <div class="fw-semibold">{{ now()->translatedFormat('l، j F Y') }}</div>
            <div class="text-muted small">{{ now()->translatedFormat('h:i A') }}</div>
        </div>
    </div>
</div>
