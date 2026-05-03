@extends('layouts.app')

@section('title', __('prescriptions.edit_prescription'))

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center">
        <a href="{{ route('doctor.prescriptions.show', $prescription) }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h5 class="mb-0">{{ __('prescriptions.edit_prescription') }}: <code>{{ $prescription->prescription_number }}</code></h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('doctor.prescriptions.update', $prescription) }}">
            @csrf
            @method('PUT')

            @if($errors->has('error'))
                <div class="alert alert-danger">{{ $errors->first('error') }}</div>
            @endif

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">{{ __('prescriptions.valid_until') }}</label>
                    <input type="date" name="valid_until"
                        value="{{ old('valid_until', $prescription->valid_until?->toDateString()) }}"
                        class="form-control" min="{{ now()->addDay()->toDateString() }}">
                </div>
                <div class="col-md-9">
                    <label class="form-label">{{ __('prescriptions.diagnosis_summary') }}</label>
                    <input type="text" name="diagnosis_summary"
                        value="{{ old('diagnosis_summary', $prescription->diagnosis_summary) }}"
                        class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('prescriptions.notes') }}</label>
                    <textarea name="notes" rows="2" class="form-control">{{ old('notes', $prescription->notes) }}</textarea>
                </div>
            </div>

            <hr>
            <h6 class="fw-semibold text-primary mb-3">{{ __('prescriptions.items') }}</h6>

            <div id="items-container">
                @foreach($prescription->items as $i => $item)
                    <div class="rx-item border rounded p-3 mb-3">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select name="items[{{ $i }}][medicine_id]" class="form-select" required>
                                    @foreach($medicines as $m)
                                        <option value="{{ $m->id }}" @selected($item->medicine_id == $m->id)>{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="items[{{ $i }}][dosage]" class="form-control"
                                    placeholder="{{ __('prescriptions.dosage') }}" value="{{ $item->dosage }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="items[{{ $i }}][frequency]" class="form-control"
                                    placeholder="{{ __('prescriptions.frequency') }}" value="{{ $item->frequency }}">
                            </div>
                            <div class="col-md-1">
                                <input type="number" name="items[{{ $i }}][duration_days]" class="form-control"
                                    placeholder="{{ __('prescriptions.days') }}" min="1" value="{{ $item->duration_days }}">
                            </div>
                            <div class="col-md-1">
                                <input type="number" name="items[{{ $i }}][quantity]" class="form-control"
                                    placeholder="{{ __('prescriptions.qty') }}" min="1" required value="{{ $item->quantity }}">
                            </div>
                            <div class="col-auto d-flex align-items-center">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-item">✕</button>
                            </div>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-md-3">
                                <input type="text" name="items[{{ $i }}][route]" class="form-control"
                                    placeholder="{{ __('prescriptions.route') }}" value="{{ $item->route }}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="items[{{ $i }}][instructions]" class="form-control"
                                    placeholder="{{ __('prescriptions.instructions') }}" value="{{ $item->instructions }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                <a href="{{ route('doctor.prescriptions.show', $prescription) }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.remove-item').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const container = document.getElementById('items-container');
        if (container.querySelectorAll('.rx-item').length > 1) {
            btn.closest('.rx-item').remove();
        }
    });
});
</script>
@endpush
