<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">{{ __('master_data.specialty_name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $specialty->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-12">
        <label class="form-label">{{ __('master_data.specialty_desc') }}</label>
        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $specialty->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('master_data.specialty_icon') }}</label>
        @if(isset($specialty) && $specialty->icon)
            <div class="mb-2">
                <img src="{{ Storage::url($specialty->icon) }}" width="60" height="60" class="rounded border" alt="">
                <small class="text-muted d-block mt-1">الأيقونة الحالية</small>
            </div>
        @endif
        <input type="file" name="icon" class="form-control @error('icon') is-invalid @enderror" accept="image/*">
        <small class="text-muted">PNG, JPG, SVG, WEBP — حجم أقصى 1MB</small>
        @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('app.status') }}</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $specialty->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">{{ __('app.active') }}</label>
        </div>
    </div>
</div>
