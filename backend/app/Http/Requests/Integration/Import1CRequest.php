<?php

namespace App\Http\Requests\Integration;

use Illuminate\Foundation\Http\FormRequest;

class Import1CRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'entity' => ['required', 'string', 'in:students,applicants,teachers'],
            'records' => ['required', 'array', 'min:1'],
            'records.*.external_id' => ['required', 'string'],
        ];
    }
}
