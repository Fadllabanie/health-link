<?php

namespace App\Http\Requests\HospitalAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StorePharmacyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('hospital_admin') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'license_number' => ['required', 'string', 'max:100', 'unique:pharmacies,license_number'],
            'email' => ['required', 'email', 'max:191', 'unique:pharmacies,email'],
            'phone' => ['required', 'string', 'max:20'],
            'country_id' => ['required', 'exists:countries,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string'],
            'type' => ['required', 'in:in_hospital,external,chain'],
            'is_24_hours' => ['boolean'],
            'opening_time' => ['nullable', 'date_format:H:i'],
            'closing_time' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'in:active,inactive,suspended'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
