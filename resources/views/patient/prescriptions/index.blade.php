@extends('layouts.app')

@section('title', __('prescriptions.all_prescriptions'))

@section('content')
<div class="row">
    <div class="col-12 mb-4 d-flex align-items-center gap-3">
        <a href="{{ route('patient.dashboard') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('prescriptions.all_prescriptions') }}</h5>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('prescriptions.prescription_number') }}</th>
                            <th>{{ __('prescriptions.doctor') }}</th>
                            <th>{{ __('prescriptions.hospital') }}</th>
                            <th>{{ __('prescriptions.issued_at') }}</th>
                            <th>{{ __('prescriptions.status') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $prescription)
                        @php
                            $badgeMap = ['pending' => 'warning', 'dispensed' => 'success', 'partially_dispensed' => 'info', 'cancelled' => 'danger', 'expired' => 'secondary'];
                            $badge = $badgeMap[$prescription->status->value] ?? 'secondary';
                        @endphp
                        <tr>
                            <td><code>{{ $prescription->prescription_number }}</code></td>
                            <td>{{ $prescription->doctor?->user?->first_name }} {{ $prescription->doctor?->user?->last_name }}</td>
                            <td>{{ $prescription->hospital?->name }}</td>
                            <td>{{ $prescription->issued_at?->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge bg-label-{{ $badge }}">
                                    {{ __('prescriptions.'.$prescription->status->value) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('patient.prescriptions.show', $prescription) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-show"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">{{ __('prescriptions.no_prescriptions') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($prescriptions->hasPages())
            <div class="card-footer">
                {{ $prescriptions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
