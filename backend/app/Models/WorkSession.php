<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkSession extends Model
{
    use HasUuids;

    public const ACTIVITY_LECTURE = 'lecture';
    public const ACTIVITY_LAB = 'lab';
    public const ACTIVITY_CONSULTATION = 'consultation';

    protected $fillable = [
        'teacher_id',
        'schedule_slot_id',
        'activity_type',
        'hours',
        'session_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'hours' => 'decimal:2',
            'session_date' => 'date',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function scheduleSlot(): BelongsTo
    {
        return $this->belongsTo(ScheduleSlot::class);
    }
}
