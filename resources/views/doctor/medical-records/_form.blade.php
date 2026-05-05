<div class="card mb-4">
    <div class="card-header"><h6 class="mb-0">{{ __('medical_records.record_details') }}</h6></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">{{ __('medical_records.visit_date') }} <span class="text-danger">*</span></label>
                <input type="datetime-local" name="visit_date" class="form-control @error('visit_date') is-invalid @enderror"
                    value="{{ old('visit_date', isset($medicalRecord) ? $medicalRecord->visit_date->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                    max="{{ now()->format('Y-m-d\TH:i') }}">
                @error('visit_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('medical_records.visit_type') }} <span class="text-danger">*</span></label>
                <select name="visit_type" class="form-select @error('visit_type') is-invalid @enderror">
                    <option value="">{{ __('app.select') }}</option>
                    @foreach($visitTypes as $type)
                        <option value="{{ $type->value }}"
                            @selected(old('visit_type', isset($medicalRecord) ? $medicalRecord->visit_type->value : '') === $type->value)>
                            {{ __('medical_records.'.$type->value) }}
                        </option>
                    @endforeach
                </select>
                @error('visit_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label">{{ __('medical_records.diagnosis') }} <span class="text-danger">*</span></label>
                <textarea name="diagnosis" rows="3"
                    class="form-control @error('diagnosis') is-invalid @enderror"
                    placeholder="{{ __('medical_records.diagnosis') }}">{{ old('diagnosis', $medicalRecord->diagnosis ?? '') }}</textarea>
                @error('diagnosis')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label">{{ __('medical_records.notes') }} <span class="text-danger">*</span></label>
                <textarea name="notes" rows="4"
                    class="form-control @error('notes') is-invalid @enderror"
                    placeholder="{{ __('medical_records.notes') }}">{{ old('notes', $medicalRecord->notes ?? '') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><h6 class="mb-0">{{ __('medical_records.attachments') }}</h6></div>
    <div class="card-body">
        <div id="attachments-container">
            <div class="row g-2 mb-2 attachment-row">
                <div class="col-md-6">
                    <input type="file" name="attachments[]"
                        class="form-control @error('attachments.0') is-invalid @enderror"
                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    @error('attachments.0')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">{{ __('app.allowed_types') ?? 'PDF, JPG, PNG, DOC, DOCX — max 10MB' }}</small>
                </div>
                <div class="col-md-5">
                    <input type="text" name="attachment_descriptions[]" class="form-control"
                        placeholder="{{ __('medical_records.attachment_description') }}">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-attachment" style="display:none">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <button type="button" id="add-attachment" class="btn btn-sm btn-outline-secondary mt-2">
            <i class="bx bx-plus me-1"></i>{{ __('medical_records.add_attachments') }}
        </button>
        @error('attachments')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
</div>

@push('scripts')
<script>
document.getElementById('add-attachment').addEventListener('click', function () {
    const container = document.getElementById('attachments-container');
    const rows = container.querySelectorAll('.attachment-row');
    if (rows.length >= 10) return;

    const row = rows[0].cloneNode(true);
    const idx = rows.length;
    row.querySelectorAll('input[type="file"]').forEach(el => {
        el.name = `attachments[]`;
        el.value = '';
    });
    row.querySelectorAll('input[type="text"]').forEach(el => {
        el.name = `attachment_descriptions[]`;
        el.value = '';
    });
    row.querySelector('.remove-attachment').style.display = '';
    container.appendChild(row);
});

document.getElementById('attachments-container').addEventListener('click', function (e) {
    if (e.target.closest('.remove-attachment')) {
        e.target.closest('.attachment-row').remove();
    }
});
</script>
@endpush
