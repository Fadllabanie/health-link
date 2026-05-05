<?php

namespace App\Http\Requests\Doctor;

use App\Enums\VisitType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateMedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('doctor') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'visit_date' => ['required', 'date', 'before_or_equal:now'],
            'visit_type' => ['required', new Enum(VisitType::class)],
            'diagnosis' => ['required', 'string', 'max:2000'],
            'notes' => ['required', 'string', 'max:5000'],
            'attachments' => ['nullable', 'array', 'max:10'],
            'attachments.*' => [
                'file',
                'max:10240',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
            ],
            'attachment_descriptions' => ['nullable', 'array'],
            'attachment_descriptions.*' => ['nullable', 'string', 'max:255'],
            'finalize' => ['nullable', 'boolean'],
        ];
    }
}
