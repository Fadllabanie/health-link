@extends('layouts.app')

@section('title', __('pharmacies.add_inventory'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <a href="{{ route('hospital-admin.pharmacies.inventory.index', $pharmacy) }}" class="btn btn-sm btn-outline-secondary me-3">
                    <i class="bx bx-arrow-back"></i>
                </a>
                <h5 class="mb-0">{{ __('pharmacies.add_inventory') }} — {{ $pharmacy->name }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hospital-admin.pharmacies.inventory.store', $pharmacy) }}">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('pharmacies.medicine') }} <span class="text-danger">*</span></label>
                            <select name="medicine_id" class="form-select @error('medicine_id') is-invalid @enderror" required>
                                <option value="">-- {{ __('app.select') }} --</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}" @selected(old('medicine_id') == $medicine->id)>
                                        {{ $medicine->name }}
                                        @if($medicine->generic_name) ({{ $medicine->generic_name }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('medicine_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('pharmacies.batch_number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="batch_number" class="form-control @error('batch_number') is-invalid @enderror"
                                value="{{ old('batch_number') }}" required>
                            @error('batch_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('pharmacies.quantity') }} <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" min="1" class="form-control @error('quantity') is-invalid @enderror"
                                value="{{ old('quantity') }}" required>
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('pharmacies.reorder_level') }} <span class="text-danger">*</span></label>
                            <input type="number" name="reorder_level" min="0" class="form-control @error('reorder_level') is-invalid @enderror"
                                value="{{ old('reorder_level', 10) }}" required>
                            @error('reorder_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('pharmacies.location') }}</label>
                            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                value="{{ old('location') }}">
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('pharmacies.unit_cost') }} <span class="text-danger">*</span></label>
                            <input type="number" name="unit_cost" min="0" step="0.01" class="form-control @error('unit_cost') is-invalid @enderror"
                                value="{{ old('unit_cost') }}" required>
                            @error('unit_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('pharmacies.selling_price') }} <span class="text-danger">*</span></label>
                            <input type="number" name="selling_price" min="0" step="0.01" class="form-control @error('selling_price') is-invalid @enderror"
                                value="{{ old('selling_price') }}" required>
                            @error('selling_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('pharmacies.supplier') }}</label>
                            <input type="text" name="supplier" class="form-control @error('supplier') is-invalid @enderror"
                                value="{{ old('supplier') }}">
                            @error('supplier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('pharmacies.manufacturing_date') }}</label>
                            <input type="date" name="manufacturing_date" class="form-control @error('manufacturing_date') is-invalid @enderror"
                                value="{{ old('manufacturing_date') }}">
                            @error('manufacturing_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('pharmacies.expiry_date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror"
                                value="{{ old('expiry_date') }}" required>
                            @error('expiry_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                        <a href="{{ route('hospital-admin.pharmacies.inventory.index', $pharmacy) }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
