<?php

namespace App\Http\Requests\HospitalAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('hospital_admin');
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'license_number' => ['required', 'string', 'max:100', 'unique:doctors,license_number'],
            'license_expires_at' => ['nullable', 'date'],
            'primary_specialty_id' => ['required', 'exists:specialties,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'secondary_specialties' => ['nullable', 'array'],
            'secondary_specialties.*' => ['exists:specialties,id'],
            'consultation_fee' => ['nullable', 'numeric', 'min:0'],
            'years_of_experience' => ['nullable', 'integer', 'min:0', 'max:60'],
            'qualifications' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'joined_at' => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => __('app.first_name'),
            'last_name' => __('app.last_name'),
            'email' => __('app.email'),
            'phone' => __('app.phone'),
            'password' => __('app.password'),
            'license_number' => __('doctors.license_number'),
            'primary_specialty_id' => __('doctors.primary_specialty'),
            'department_id' => __('doctors.department'),
        ];
    }
}
