<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\StoreClassroomRequest;
use App\Models\Classroom;
use App\Models\ScheduleSlot;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Classroom::query()->where('is_active', true)->orderBy('building')->orderBy('number')->get());
    }

    public function store(StoreClassroomRequest $request): JsonResponse
    {
        $classroom = Classroom::create($request->validated());

        return response()->json($classroom, 201);
    }

    public function show(Classroom $classroom): JsonResponse
    {
        return response()->json($classroom);
    }

    public function update(StoreClassroomRequest $request, Classroom $classroom): JsonResponse
    {
        $classroom->update($request->validated());

        return response()->json($classroom->fresh());
    }

    public function availability(Request $request): JsonResponse
    {
        $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'date' => ['required', 'date'],
        ]);

        $date = Carbon::parse($request->date('date'));
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        $busy = ScheduleSlot::query()
            ->where('classroom_id', $request->integer('classroom_id'))
            ->where('starts_at', '<', $endOfDay)
            ->where('ends_at', '>', $startOfDay)
            ->orderBy('starts_at')
            ->get(['id', 'subject', 'starts_at', 'ends_at']);

        return response()->json([
            'classroom_id' => $request->integer('classroom_id'),
            'date' => $date->toDateString(),
            'busy_slots' => $busy,
        ]);
    }
}
