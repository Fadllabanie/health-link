<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">{{ __('master_data.country_name') }} <span class="text-danger">*</span></label>
        <select name="country_id" class="form-select @error('country_id') is-invalid @enderror" required>
            <option value="">-- اختر الدولة --</option>
            @foreach($countries as $c)
                <option value="{{ $c->id }}" {{ old('country_id', $city->country_id ?? '') == $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>
        @error('country_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('master_data.city_name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $city->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('master_data.latitude') }}</label>
        <input type="number" step="any" name="latitude" class="form-control @error('latitude') is-invalid @enderror"
               value="{{ old('latitude', $city->latitude ?? '') }}">
        @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('master_data.longitude') }}</label>
        <input type="number" step="any" name="longitude" class="form-control @error('longitude') is-invalid @enderror"
               value="{{ old('longitude', $city->longitude ?? '') }}">
        @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('app.status') }}</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $city->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">{{ __('app.active') }}</label>
        </div>
    </div>
</div>
