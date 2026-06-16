<?php

namespace App\Services;

use App\Models\ScheduleSlot;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ScheduleConflictService
{
    public function findConflicts(
        int $teacherId,
        int $classroomId,
        Carbon $startsAt,
        Carbon $endsAt,
        ?string $excludeSlotId = null,
    ): Collection {
        $query = ScheduleSlot::query()
            ->where(function ($q) use ($startsAt, $endsAt) {
                $q->where('starts_at', '<', $endsAt)
                    ->where('ends_at', '>', $startsAt);
            })
            ->where(function ($q) use ($teacherId, $classroomId) {
                $q->where('teacher_id', $teacherId)
                    ->orWhere('classroom_id', $classroomId);
            });

        if ($excludeSlotId) {
            $query->where('id', '!=', $excludeSlotId);
        }

        return $query->with(['teacher.user', 'classroom'])->get()->map(function (ScheduleSlot $slot) use ($teacherId, $classroomId) {
            $conflicts = [];

            if ($slot->teacher_id === $teacherId) {
                $conflicts[] = 'teacher';
            }

            if ($slot->classroom_id === $classroomId) {
                $conflicts[] = 'classroom';
            }

            return [
                'slot' => $slot,
                'conflicts' => $conflicts,
            ];
        });
    }

    public function hasConflicts(
        int $teacherId,
        int $classroomId,
        Carbon $startsAt,
        Carbon $endsAt,
        ?string $excludeSlotId = null,
    ): bool {
        return $this->findConflicts($teacherId, $classroomId, $startsAt, $endsAt, $excludeSlotId)->isNotEmpty();
    }
}
