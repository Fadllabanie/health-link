<?php

namespace App\Http\Requests\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('pharmacist') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'medicine_id' => ['required', 'exists:medicines,id'],
            'batch_number' => ['required', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'manufacturing_date' => ['nullable', 'date'],
            'expiry_date' => ['required', 'date', 'after:today'],
            'supplier' => ['nullable', 'string', 'max:191'],
            'location' => ['nullable', 'string', 'max:100'],
        ];
    }
}
