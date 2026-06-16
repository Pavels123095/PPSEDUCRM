<?php

namespace App\Http\Requests\Applicant;

use App\Models\Applicant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicantStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(Applicant::STATUSES)],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Укажите статус.',
            'status.in' => 'Недопустимый статус абитуриента.',
        ];
    }
}
