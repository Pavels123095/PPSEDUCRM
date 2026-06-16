<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'study_group_id',
        'course',
        'external_id',
        'sync_status',
        'last_synced_at',
        'compliance_metadata',
    ];

    protected function casts(): array
    {
        return [
            'compliance_metadata' => 'array',
            'last_synced_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function studyGroup(): BelongsTo
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function applicant(): HasOne
    {
        return $this->hasOne(Applicant::class);
    }

    public function scheduleSlots(): BelongsToMany
    {
        return $this->belongsToMany(ScheduleSlot::class, 'schedule_slot_student')
            ->withTimestamps();
    }
}
