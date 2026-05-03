@extends('layouts.app')

@section('title', __('pharmacies.inventory') . ' — ' . $inventory->medicine?->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <a href="{{ route('pharmacy.inventory.show', $inventory) }}" class="btn btn-sm btn-outline-secondary me-3">
                    <i class="bx bx-arrow-back"></i>
                </a>
                <h5 class="mb-0">{{ __('app.edit') }}: {{ $inventory->medicine?->name }}</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <span class="iconify" data-icon="tabler:info-circle"></span>
                    {{ __('pharmacies.batch_number') }}: <strong>{{ $inventory->batch_number }}</strong> |
                    {{ __('pharmacies.expiry_date') }}: <strong>{{ $inventory->expiry_date?->format('Y-m-d') }}</strong>
                </div>

                <form method="POST" action="{{ route('pharmacy.inventory.update', $inventory) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('pharmacies.quantity') }} <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" min="0" class="form-control @error('quantity') is-invalid @enderror"
                                value="{{ old('quantity', $inventory->quantity_in_stock) }}" required>
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('pharmacies.selling_price') }} <span class="text-danger">*</span></label>
                            <input type="number" name="selling_price" min="0" step="0.01" class="form-control @error('selling_price') is-invalid @enderror"
                                value="{{ old('selling_price', $inventory->selling_price) }}" required>
                            @error('selling_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('pharmacies.reorder_level') }} <span class="text-danger">*</span></label>
                            <input type="number" name="reorder_level" min="0" class="form-control @error('reorder_level') is-invalid @enderror"
                                value="{{ old('reorder_level', $inventory->reorder_level) }}" required>
                            @error('reorder_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('pharmacies.location') }}</label>
                            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                value="{{ old('location', $inventory->location) }}">
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                        <a href="{{ route('pharmacy.inventory.show', $inventory) }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
