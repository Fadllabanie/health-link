@extends('layouts.app')

@section('title', __('prescriptions.new_prescription'))

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center">
        <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('prescriptions.new_prescription') }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('doctor.prescriptions.store') }}">
            @csrf

            @if($errors->has('error'))
                <div class="alert alert-danger">{{ $errors->first('error') }}</div>
            @endif

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">{{ __('patients.patient') }} <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('app.select') }} --</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}" @selected(old('patient_id', $selectedPatient?->id) == $p->id)>
                                {{ $p->user->first_name }} {{ $p->user->last_name }} ({{ $p->medical_record_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('prescriptions.valid_until') }}</label>
                    <input type="date" name="valid_until" value="{{ old('valid_until') }}"
                        class="form-control @error('valid_until') is-invalid @enderror"
                        min="{{ now()->addDay()->toDateString() }}">
                    @error('valid_until')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-9">
                    <label class="form-label">{{ __('prescriptions.diagnosis_summary') }}</label>
                    <input type="text" name="diagnosis_summary" value="{{ old('diagnosis_summary') }}"
                        class="form-control @error('diagnosis_summary') is-invalid @enderror">
                    @error('diagnosis_summary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('prescriptions.notes') }}</label>
                    <textarea name="notes" rows="2" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>
            <h6 class="fw-semibold text-primary mb-3">{{ __('prescriptions.items') }}</h6>
            @error('items')<div class="alert alert-danger">{{ $message }}</div>@enderror

            <div id="items-container">
                @include('doctor.prescriptions._item_row', ['index' => 0, 'medicines' => $medicines])
            </div>

            <button type="button" id="add-item" class="btn btn-outline-secondary btn-sm mb-4">
                <i class="bx bx-plus me-1"></i>{{ __('prescriptions.add_item') }}
            </button>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    let itemIndex = 1;
    const container = document.getElementById('items-container');
    const addBtn = document.getElementById('add-item');
    const medicines = @json($medicines->map(fn($m) => ['id' => $m->id, 'name' => $m->name]));

    addBtn.addEventListener('click', function () {
        const row = buildRow(itemIndex++, medicines);
        container.appendChild(row);
        bindRemove(row);
    });

    function buildRow(idx, meds) {
        const wrapper = document.createElement('div');
        wrapper.className = 'rx-item border rounded p-3 mb-3';

        const select = document.createElement('select');
        select.name = 'items[' + idx + '][medicine_id]';
        select.className = 'form-select mb-2';
        select.required = true;

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = '-- {{ __('app.select') }} --';
        select.appendChild(placeholder);

        meds.forEach(function (m) {
            const opt = document.createElement('option');
            opt.value = m.id;
            opt.textContent = m.name;
            select.appendChild(opt);
        });

        const fields = [
            { name: 'dosage', placeholder: '{{ __('prescriptions.dosage') }}', type: 'text' },
            { name: 'frequency', placeholder: '{{ __('prescriptions.frequency') }}', type: 'text' },
            { name: 'duration_days', placeholder: '{{ __('prescriptions.duration_days') }}', type: 'number' },
            { name: 'quantity', placeholder: '{{ __('prescriptions.quantity') }}', type: 'number', required: true },
            { name: 'route', placeholder: '{{ __('prescriptions.route') }}', type: 'text' },
            { name: 'instructions', placeholder: '{{ __('prescriptions.instructions') }}', type: 'text' },
        ];

        const row = document.createElement('div');
        row.className = 'row g-2';

        const selCol = document.createElement('div');
        selCol.className = 'col-md-4';
        selCol.appendChild(select);
        row.appendChild(selCol);

        fields.forEach(function (f) {
            const col = document.createElement('div');
            col.className = 'col-md-2';
            const input = document.createElement('input');
            input.type = f.type;
            input.name = 'items[' + idx + '][' + f.name + ']';
            input.placeholder = f.placeholder;
            input.className = 'form-control';
            if (f.required) { input.required = true; input.min = '1'; }
            col.appendChild(input);
            row.appendChild(col);
        });

        const removeCol = document.createElement('div');
        removeCol.className = 'col-auto d-flex align-items-end';
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-outline-danger btn-sm remove-item';
        removeBtn.textContent = '✕';
        removeCol.appendChild(removeBtn);
        row.appendChild(removeCol);

        wrapper.appendChild(row);
        return wrapper;
    }

    function bindRemove(row) {
        row.querySelector('.remove-item')?.addEventListener('click', function () {
            if (container.querySelectorAll('.rx-item').length > 1) {
                row.remove();
            }
        });
    }

    container.querySelectorAll('.rx-item').forEach(bindRemove);
})();
</script>
@endpush
