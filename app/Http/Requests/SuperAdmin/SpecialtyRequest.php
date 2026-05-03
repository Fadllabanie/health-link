<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SpecialtyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $specialtyId = $this->route('specialty')?->id;

        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('specialties', 'name')->ignore($specialtyId)->whereNull('deleted_at')],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:1024'],
            'is_active' => ['boolean'],
        ];
    }
}
