<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\StoreScheduleSlotRequest;
use App\Http\Requests\Schedule\UpdateScheduleSlotRequest;
use App\Models\ScheduleSlot;
use App\Services\ScheduleConflictService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleSlotController extends Controller
{
    public function __construct(private ScheduleConflictService $conflictService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = ScheduleSlot::query()->with(['teacher.user', 'classroom', 'studyGroup']);

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->integer('teacher_id'));
        }

        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->integer('classroom_id'));
        }

        if ($request->filled('from')) {
            $query->where('starts_at', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->where('ends_at', '<=', $request->date('to'));
        }

        return response()->json($query->orderBy('starts_at')->paginate(50));
    }

    public function store(StoreScheduleSlotRequest $request): JsonResponse
    {
        $data = $request->validated();
        $startsAt = Carbon::parse($data['starts_at']);
        $endsAt = Carbon::parse($data['ends_at']);

        $conflicts = $this->conflictService->findConflicts(
            $data['teacher_id'],
            $data['classroom_id'],
            $startsAt,
            $endsAt,
        );

        if ($conflicts->isNotEmpty()) {
            return response()->json([
                'message' => 'Обнаружен конфликт расписания.',
                'conflicts' => $conflicts,
            ], 422);
        }

        $slot = ScheduleSlot::create($data);

        return response()->json($slot->load(['teacher.user', 'classroom', 'studyGroup']), 201);
    }

    public function show(ScheduleSlot $scheduleSlot): JsonResponse
    {
        return response()->json($scheduleSlot->load(['teacher.user', 'classroom', 'studyGroup', 'students.user']));
    }

    public function update(UpdateScheduleSlotRequest $request, ScheduleSlot $scheduleSlot): JsonResponse
    {
        $data = $request->validated();

        $teacherId = $data['teacher_id'] ?? $scheduleSlot->teacher_id;
        $classroomId = $data['classroom_id'] ?? $scheduleSlot->classroom_id;
        $startsAt = Carbon::parse($data['starts_at'] ?? $scheduleSlot->starts_at);
        $endsAt = Carbon::parse($data['ends_at'] ?? $scheduleSlot->ends_at);

        $conflicts = $this->conflictService->findConflicts(
            $teacherId,
            $classroomId,
            $startsAt,
            $endsAt,
            $scheduleSlot->id,
        );

        if ($conflicts->isNotEmpty()) {
            return response()->json([
                'message' => 'Обнаружен конфликт расписания.',
                'conflicts' => $conflicts,
            ], 422);
        }

        $scheduleSlot->update($data);

        return response()->json($scheduleSlot->fresh()->load(['teacher.user', 'classroom', 'studyGroup']));
    }

    public function destroy(ScheduleSlot $scheduleSlot): JsonResponse
    {
        $scheduleSlot->delete();

        return response()->json(['message' => 'Слот расписания удалён.']);
    }
}
