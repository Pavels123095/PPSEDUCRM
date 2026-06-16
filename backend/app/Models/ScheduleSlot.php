<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduleSlot extends Model
{
    use HasUuids;

    public const TYPE_LECTURE = 'lecture';
    public const TYPE_LAB = 'lab';
    public const TYPE_CONSULTATION = 'consultation';

    public const TYPES = [
        self::TYPE_LECTURE,
        self::TYPE_LAB,
        self::TYPE_CONSULTATION,
    ];

    protected $fillable = [
        'subject',
        'type',
        'starts_at',
        'ends_at',
        'teacher_id',
        'classroom_id',
        'study_group_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function studyGroup(): BelongsTo
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'schedule_slot_student')
            ->withTimestamps();
    }

    public function workSessions(): HasMany
    {
        return $this->hasMany(WorkSession::class);
    }
}
