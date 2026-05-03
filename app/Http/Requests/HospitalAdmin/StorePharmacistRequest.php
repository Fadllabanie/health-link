<?php

namespace App\Http\Requests\HospitalAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StorePharmacistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('hospital_admin') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'license_number' => ['nullable', 'string', 'max:100'],
            'position' => ['nullable', 'string', 'max:100'],
        ];
    }
}
