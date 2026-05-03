<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $countryId = $this->route('country')?->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'size:2', Rule::unique('countries', 'code')->ignore($countryId)->whereNull('deleted_at')],
            'code3' => ['required', 'string', 'size:3', Rule::unique('countries', 'code3')->ignore($countryId)->whereNull('deleted_at')],
            'phone_code' => ['nullable', 'string', 'max:10'],
            'currency_code' => ['nullable', 'string', 'size:3'],
            'is_active' => ['boolean'],
        ];
    }
}
