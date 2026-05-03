<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StoreHospitalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('super_admin') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'license_number' => ['required', 'string', 'max:100', 'unique:hospitals,license_number'],
            'email' => ['required', 'email', 'max:191', 'unique:hospitals,email'],
            'phone' => ['required', 'string', 'max:20'],
            'alternate_phone' => ['nullable', 'string', 'max:20'],
            'country_id' => ['required', 'exists:countries,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'website' => ['nullable', 'url', 'max:191'],
            'description' => ['nullable', 'string'],
            'established_date' => ['nullable', 'date'],
            'bed_capacity' => ['nullable', 'integer', 'min:1'],
            'subscription_plan' => ['nullable', 'in:free,basic,premium,enterprise'],
            'subscription_expires_at' => ['nullable', 'date', 'after:today'],
            'specialty_ids' => ['nullable', 'array'],
            'specialty_ids.*' => ['exists:specialties,id'],
            // First hospital admin
            'admin_first_name' => ['required', 'string', 'max:100'],
            'admin_last_name' => ['required', 'string', 'max:100'],
            'admin_email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'admin_phone' => ['nullable', 'string', 'max:20'],
            'admin_password' => ['required', 'string', 'min:8'],
        ];
    }
}
