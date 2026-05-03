<?php

namespace App\Http\Requests\HospitalAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('hospital_admin') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            // User account
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8'],
            // Medical info
            'city_id' => ['nullable', 'exists:cities,id'],
            'blood_type' => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'height_cm' => ['nullable', 'numeric', 'min:50', 'max:250'],
            'weight_kg' => ['nullable', 'numeric', 'min:10', 'max:300'],
            'allergies' => ['nullable', 'string'],
            'chronic_conditions' => ['nullable', 'string'],
            'current_medications' => ['nullable', 'string'],
            // Emergency contact
            'emergency_contact_name' => ['nullable', 'string', 'max:150'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:50'],
            // Insurance
            'insurance_provider' => ['nullable', 'string', 'max:150'],
            'insurance_policy_number' => ['nullable', 'string', 'max:100'],
            // Other
            'marital_status' => ['nullable', 'in:single,married,divorced,widowed'],
            'occupation' => ['nullable', 'string', 'max:100'],
        ];
    }
}
