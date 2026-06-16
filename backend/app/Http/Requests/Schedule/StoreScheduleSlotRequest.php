<?php

namespace App\Http\Requests\Schedule;

use App\Models\ScheduleSlot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreScheduleSlotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(ScheduleSlot::TYPES)],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'study_group_id' => ['nullable', 'exists:study_groups,id'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
