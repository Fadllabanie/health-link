<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">{{ __('master_data.country_name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $country->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('master_data.country_code') }} <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
               value="{{ old('code', $country->code ?? '') }}" maxlength="2" style="text-transform:uppercase" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('master_data.country_code3') }} <span class="text-danger">*</span></label>
        <input type="text" name="code3" class="form-control @error('code3') is-invalid @enderror"
               value="{{ old('code3', $country->code3 ?? '') }}" maxlength="3" style="text-transform:uppercase" required>
        @error('code3')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('master_data.phone_code') }}</label>
        <input type="text" name="phone_code" class="form-control @error('phone_code') is-invalid @enderror"
               value="{{ old('phone_code', $country->phone_code ?? '') }}" maxlength="10">
        @error('phone_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('master_data.currency_code') }}</label>
        <input type="text" name="currency_code" class="form-control @error('currency_code') is-invalid @enderror"
               value="{{ old('currency_code', $country->currency_code ?? '') }}" maxlength="3" style="text-transform:uppercase">
        @error('currency_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('app.status') }}</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $country->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">{{ __('app.active') }}</label>
        </div>
    </div>
</div>
