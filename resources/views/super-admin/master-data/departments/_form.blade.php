<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">المستشفى <span class="text-danger">*</span></label>
        <select name="hospital_id" class="form-select @error('hospital_id') is-invalid @enderror" required>
            <option value="">-- اختر المستشفى --</option>
            @foreach($hospitals as $h)
                <option value="{{ $h->id }}" {{ old('hospital_id', $department->hospital_id ?? '') == $h->id ? 'selected' : '' }}>
                    {{ $h->name }}
                </option>
            @endforeach
        </select>
        @error('hospital_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('master_data.department_name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $department->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('master_data.department_code') }}</label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
               value="{{ old('code', $department->code ?? '') }}" maxlength="20">
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-12">
        <label class="form-label">الوصف</label>
        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $department->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('app.status') }}</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $department->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">{{ __('app.active') }}</label>
        </div>
    </div>
</div>
