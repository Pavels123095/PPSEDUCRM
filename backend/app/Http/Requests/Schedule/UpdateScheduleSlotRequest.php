<?php

namespace App\Http\Requests\Schedule;

use App\Models\ScheduleSlot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateScheduleSlotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', Rule::in(ScheduleSlot::TYPES)],
            'starts_at' => ['sometimes', 'date'],
            'ends_at' => ['sometimes', 'date', 'after:starts_at'],
            'teacher_id' => ['sometimes', 'exists:teachers,id'],
            'classroom_id' => ['sometimes', 'exists:classrooms,id'],
            'study_group_id' => ['nullable', 'exists:study_groups,id'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
