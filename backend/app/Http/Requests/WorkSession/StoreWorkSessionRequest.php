<?php

namespace App\Http\Requests\WorkSession;

use App\Models\WorkSession;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'schedule_slot_id' => ['nullable', 'uuid', 'exists:schedule_slots,id'],
            'activity_type' => ['required', Rule::in([
                WorkSession::ACTIVITY_LECTURE,
                WorkSession::ACTIVITY_LAB,
                WorkSession::ACTIVITY_CONSULTATION,
            ])],
            'hours' => ['required', 'numeric', 'min:0.25', 'max:24'],
            'session_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
