<?php

namespace App\Http\Requests\HospitalAdmin;

use App\Enums\MedicineForm;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateMedicineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('hospital_admin');
    }

    public function rules(): array
    {
        $medicineId = $this->route('medicine')->id;

        return [
            'name' => ['required', 'string', 'max:191'],
            'generic_name' => ['nullable', 'string', 'max:191'],
            'brand_name' => ['nullable', 'string', 'max:191'],
            'barcode' => ['nullable', 'string', 'max:100', "unique:medicines,barcode,{$medicineId}"],
            'category_id' => ['nullable', 'exists:medicine_categories,id'],
            'manufacturer' => ['nullable', 'string', 'max:191'],
            'form' => ['required', new Enum(MedicineForm::class)],
            'strength' => ['nullable', 'string', 'max:50'],
            'unit' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'side_effects' => ['nullable', 'string'],
            'contraindications' => ['nullable', 'string'],
            'dosage_instructions' => ['nullable', 'string'],
            'requires_prescription' => ['boolean'],
            'is_controlled' => ['boolean'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'requires_prescription' => $this->boolean('requires_prescription'),
            'is_controlled' => $this->boolean('is_controlled'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
