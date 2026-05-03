<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $deptId = $this->route('department')?->id;

        return [
            'hospital_id' => ['required', 'exists:hospitals,id'],
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('departments')->where('hospital_id', $this->input('hospital_id'))->ignore($deptId)->whereNull('deleted_at'),
            ],
            'code' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ];
    }
}
