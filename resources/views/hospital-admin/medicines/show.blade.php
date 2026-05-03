@extends('layouts.app')

@section('title', $medicine->name)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="{{ route('hospital-admin.medicines.index') }}" class="btn btn-sm btn-outline-secondary me-3">
                        <i class="bx bx-arrow-back"></i>
                    </a>
                    <h5 class="mb-0">{{ $medicine->name }}</h5>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('hospital-admin.medicines.edit', $medicine) }}" class="btn btn-sm btn-primary">
                        <i class="bx bx-edit me-1"></i>{{ __('app.edit') }}
                    </a>
                    <form method="POST" action="{{ route('hospital-admin.medicines.destroy', $medicine) }}"
                        onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bx bx-trash me-1"></i>{{ __('app.delete') }}
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">{{ __('medicines.name') }}</div>
                        <div class="fw-semibold">{{ $medicine->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">{{ __('medicines.generic_name') }}</div>
                        <div>{{ $medicine->generic_name ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">{{ __('medicines.brand_name') }}</div>
                        <div>{{ $medicine->brand_name ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">{{ __('medicines.barcode') }}</div>
                        <div>{{ $medicine->barcode ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">{{ __('medicines.category') }}</div>
                        <div>{{ $medicine->category?->name ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">{{ __('medicines.manufacturer') }}</div>
                        <div>{{ $medicine->manufacturer ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('medicines.form') }}</div>
                        <div>{{ __('medicines.'.$medicine->form->value) }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('medicines.strength') }}</div>
                        <div>{{ $medicine->strength ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('medicines.unit') }}</div>
                        <div>{{ $medicine->unit ?? '—' }}</div>
                    </div>
                </div>

                @if($medicine->description)
                    <hr>
                    <h6 class="fw-semibold">{{ __('medicines.description') }}</h6>
                    <p class="mb-0">{{ $medicine->description }}</p>
                @endif

                @if($medicine->side_effects)
                    <hr>
                    <h6 class="fw-semibold">{{ __('medicines.side_effects') }}</h6>
                    <p class="mb-0">{{ $medicine->side_effects }}</p>
                @endif

                @if($medicine->contraindications)
                    <hr>
                    <h6 class="fw-semibold">{{ __('medicines.contraindications') }}</h6>
                    <p class="mb-0">{{ $medicine->contraindications }}</p>
                @endif

                @if($medicine->dosage_instructions)
                    <hr>
                    <h6 class="fw-semibold">{{ __('medicines.dosage_instructions') }}</h6>
                    <p class="mb-0">{{ $medicine->dosage_instructions }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Image --}}
        <div class="card mb-4">
            <div class="card-body text-center">
                @if($medicine->image_url)
                    <img src="{{ $medicine->image_url }}" alt="{{ $medicine->name }}"
                        class="img-fluid rounded" style="max-height:200px">
                @else
                    <div class="text-muted py-4">
                        <i class="bx bx-image fs-1"></i>
                        <p class="mb-0">{{ __('medicines.no_image') }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Flags --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>{{ __('medicines.requires_prescription') }}</span>
                    @if($medicine->requires_prescription)
                        <span class="badge bg-label-warning">{{ __('app.yes') }}</span>
                    @else
                        <span class="badge bg-label-success">{{ __('app.no') }}</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>{{ __('medicines.is_controlled') }}</span>
                    @if($medicine->is_controlled)
                        <span class="badge bg-label-danger">{{ __('app.yes') }}</span>
                    @else
                        <span class="badge bg-label-secondary">{{ __('app.no') }}</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between">
                    <span>{{ __('app.status') }}</span>
                    @if($medicine->is_active)
                        <span class="badge bg-label-success">{{ __('app.active') }}</span>
                    @else
                        <span class="badge bg-label-secondary">{{ __('app.inactive') }}</span>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <form method="POST"
                    action="{{ route('hospital-admin.medicines.toggle-status', $medicine) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-outline-warning w-100">
                        {{ $medicine->is_active ? __('medicines.medicine_disabled') : __('medicines.medicine_enabled') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
