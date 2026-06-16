<?php

namespace App\Http\Requests\Applicant;

use App\Rules\SnilsRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'last_name' => ['sometimes', 'string', 'max:100'],
            'first_name' => ['sometimes', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'snils' => ['nullable', 'string', new SnilsRule()],
            'passport_series' => ['nullable', 'digits:4'],
            'passport_number' => ['nullable', 'digits:6'],
            'manager_id' => ['nullable', 'exists:managers,id'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
