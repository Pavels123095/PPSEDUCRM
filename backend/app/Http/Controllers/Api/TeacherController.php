<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\WorkSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Teacher::query()->with('user')->get()
        );
    }

    public function show(Teacher $teacher): JsonResponse
    {
        return response()->json($teacher->load('user'));
    }

    public function hoursReport(Request $request, Teacher $teacher): JsonResponse
    {
        $request->validate([
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
        ]);

        $sessions = WorkSession::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('session_date', '>=', $request->date('from'))
            ->whereDate('session_date', '<=', $request->date('to'))
            ->get();

        $byType = $sessions->groupBy('activity_type')->map(fn ($group) => round($group->sum('hours'), 2));

        return response()->json([
            'teacher' => $teacher->load('user'),
            'period' => [
                'from' => $request->date('from')->toDateString(),
                'to' => $request->date('to')->toDateString(),
            ],
            'total_hours' => round($sessions->sum('hours'), 2),
            'by_activity_type' => $byType,
            'sessions' => $sessions,
        ]);
    }
}
