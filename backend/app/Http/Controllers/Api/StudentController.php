<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function schedule(Request $request): JsonResponse
    {
        $student = $request->user()->student;

        if (! $student) {
            return response()->json(['message' => 'Профиль студента не найден.'], 404);
        }

        $from = $request->filled('from')
            ? Carbon::parse($request->input('from'))->startOfDay()
            : now()->startOfWeek();

        $to = $request->filled('to')
            ? Carbon::parse($request->input('to'))->endOfDay()
            : now()->endOfWeek();

        $groupSlots = collect();
        if ($student->study_group_id) {
            $groupSlots = \App\Models\ScheduleSlot::query()
                ->where('study_group_id', $student->study_group_id)
                ->where('starts_at', '>=', $from)
                ->where('ends_at', '<=', $to)
                ->with(['teacher.user', 'classroom'])
                ->orderBy('starts_at')
                ->get();
        }

        $individualSlots = $student->scheduleSlots()
            ->where('starts_at', '>=', $from)
            ->where('ends_at', '<=', $to)
            ->with(['teacher.user', 'classroom'])
            ->orderBy('starts_at')
            ->get();

        $slots = $groupSlots->merge($individualSlots)->unique('id')->sortBy('starts_at')->values();

        return response()->json([
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'schedule' => $slots,
        ]);
    }

    public function profile(Request $request): JsonResponse
    {
        $user = $request->user()->load(['student.studyGroup']);

        if (! $user->student) {
            return response()->json(['message' => 'Профиль студента не найден.'], 404);
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'student' => $user->student,
            'study_group' => $user->student->studyGroup,
        ]);
    }

    public function notifications(Request $request): JsonResponse
    {
        return response()->json(
            $request->user()->notifications()->latest()->paginate(20)
        );
    }
}
