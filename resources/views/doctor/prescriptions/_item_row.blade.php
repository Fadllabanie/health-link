<div class="rx-item border rounded p-3 mb-3">
    <div class="row g-2">
        <div class="col-md-4">
            <select name="items[{{ $index }}][medicine_id]" class="form-select @error("items.{$index}.medicine_id") is-invalid @enderror" required>
                <option value="">-- {{ __('app.select') }} --</option>
                @foreach($medicines as $m)
                    <option value="{{ $m->id }}" @selected(old("items.{$index}.medicine_id") == $m->id)>{{ $m->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" name="items[{{ $index }}][dosage]" class="form-control"
                placeholder="{{ __('prescriptions.dosage') }}" value="{{ old("items.{$index}.dosage") }}">
        </div>
        <div class="col-md-2">
            <input type="text" name="items[{{ $index }}][frequency]" class="form-control"
                placeholder="{{ __('prescriptions.frequency') }}" value="{{ old("items.{$index}.frequency") }}">
        </div>
        <div class="col-md-1">
            <input type="number" name="items[{{ $index }}][duration_days]" class="form-control"
                placeholder="{{ __('prescriptions.days') }}" min="1" value="{{ old("items.{$index}.duration_days") }}">
        </div>
        <div class="col-md-1">
            <input type="number" name="items[{{ $index }}][quantity]" class="form-control"
                placeholder="{{ __('prescriptions.qty') }}" min="1" required value="{{ old("items.{$index}.quantity") }}">
        </div>
        <div class="col-auto d-flex align-items-center">
            <button type="button" class="btn btn-outline-danger btn-sm remove-item">✕</button>
        </div>
    </div>
    <div class="row g-2 mt-1">
        <div class="col-md-3">
            <input type="text" name="items[{{ $index }}][route]" class="form-control"
                placeholder="{{ __('prescriptions.route') }}" value="{{ old("items.{$index}.route") }}">
        </div>
        <div class="col-md-6">
            <input type="text" name="items[{{ $index }}][instructions]" class="form-control"
                placeholder="{{ __('prescriptions.instructions') }}" value="{{ old("items.{$index}.instructions") }}">
        </div>
    </div>
</div>
