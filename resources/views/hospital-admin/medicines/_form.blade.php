{{-- Basic Info --}}
<h6 class="fw-semibold text-primary mb-3">{{ __('medicines.medicine') }}</h6>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label">{{ __('medicines.name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $medicine->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('medicines.generic_name') }}</label>
        <input type="text" name="generic_name"
            class="form-control @error('generic_name') is-invalid @enderror"
            value="{{ old('generic_name', $medicine->generic_name ?? '') }}">
        @error('generic_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('medicines.brand_name') }}</label>
        <input type="text" name="brand_name"
            class="form-control @error('brand_name') is-invalid @enderror"
            value="{{ old('brand_name', $medicine->brand_name ?? '') }}">
        @error('brand_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('medicines.barcode') }}</label>
        <input type="text" name="barcode"
            class="form-control @error('barcode') is-invalid @enderror"
            value="{{ old('barcode', $medicine->barcode ?? '') }}">
        @error('barcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('medicines.category') }}</label>
        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
            <option value="">-- {{ __('medicines.all_categories') }} --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    @selected(old('category_id', $medicine->category_id ?? '') == $cat->id)>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('medicines.manufacturer') }}</label>
        <input type="text" name="manufacturer"
            class="form-control @error('manufacturer') is-invalid @enderror"
            value="{{ old('manufacturer', $medicine->manufacturer ?? '') }}">
        @error('manufacturer')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<hr>
<h6 class="fw-semibold text-primary mb-3">{{ __('medicines.form') }} & {{ __('medicines.strength') }}</h6>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">{{ __('medicines.form') }} <span class="text-danger">*</span></label>
        <select name="form" class="form-select @error('form') is-invalid @enderror" required>
            <option value="">-- {{ __('app.select') }} --</option>
            @foreach(\App\Enums\MedicineForm::cases() as $f)
                <option value="{{ $f->value }}"
                    @selected(old('form', isset($medicine) ? $medicine->form->value : '') === $f->value)>
                    {{ __('medicines.'.$f->value) }}
                </option>
            @endforeach
        </select>
        @error('form')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('medicines.strength') }}</label>
        <input type="text" name="strength"
            class="form-control @error('strength') is-invalid @enderror"
            value="{{ old('strength', $medicine->strength ?? '') }}"
            placeholder="مثال: 500mg">
        @error('strength')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('medicines.unit') }}</label>
        <input type="text" name="unit"
            class="form-control @error('unit') is-invalid @enderror"
            value="{{ old('unit', $medicine->unit ?? '') }}"
            placeholder="مثال: mg">
        @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<hr>
<h6 class="fw-semibold text-primary mb-3">{{ __('medicines.description') }}</h6>
<div class="row g-3 mb-4">
    <div class="col-12">
        <label class="form-label">{{ __('medicines.description') }}</label>
        <textarea name="description" rows="3"
            class="form-control @error('description') is-invalid @enderror">{{ old('description', $medicine->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('medicines.side_effects') }}</label>
        <textarea name="side_effects" rows="3"
            class="form-control @error('side_effects') is-invalid @enderror">{{ old('side_effects', $medicine->side_effects ?? '') }}</textarea>
        @error('side_effects')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('medicines.contraindications') }}</label>
        <textarea name="contraindications" rows="3"
            class="form-control @error('contraindications') is-invalid @enderror">{{ old('contraindications', $medicine->contraindications ?? '') }}</textarea>
        @error('contraindications')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">{{ __('medicines.dosage_instructions') }}</label>
        <textarea name="dosage_instructions" rows="2"
            class="form-control @error('dosage_instructions') is-invalid @enderror">{{ old('dosage_instructions', $medicine->dosage_instructions ?? '') }}</textarea>
        @error('dosage_instructions')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<hr>
<h6 class="fw-semibold text-primary mb-3">{{ __('app.status') }}</h6>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">{{ __('medicines.image') }}</label>
        @isset($medicine)
            @if($medicine->image_url)
                <div class="mb-2">
                    <img src="{{ $medicine->image_url }}" alt="{{ $medicine->name }}"
                        class="img-thumbnail" style="max-height:100px">
                    <div class="form-text">{{ __('medicines.current_image') }}</div>
                </div>
            @endif
        @endisset
        <input type="file" name="image" accept="image/*"
            class="form-control @error('image') is-invalid @enderror">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8 d-flex align-items-end gap-4 flex-wrap pb-1">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="requires_prescription"
                id="requires_prescription" value="1"
                @checked(old('requires_prescription', $medicine->requires_prescription ?? true))>
            <label class="form-check-label" for="requires_prescription">
                {{ __('medicines.requires_prescription') }}
            </label>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_controlled"
                id="is_controlled" value="1"
                @checked(old('is_controlled', $medicine->is_controlled ?? false))>
            <label class="form-check-label" for="is_controlled">
                {{ __('medicines.is_controlled') }}
            </label>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active"
                id="is_active" value="1"
                @checked(old('is_active', $medicine->is_active ?? true))>
            <label class="form-check-label" for="is_active">
                {{ __('medicines.is_active') }}
            </label>
        </div>
    </div>
</div>
