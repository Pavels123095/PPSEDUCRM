<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkSession\StoreWorkSessionRequest;
use App\Models\WorkSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkSessionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = WorkSession::query()->with(['teacher.user', 'scheduleSlot']);

        if ($request->user()->hasRole('teacher') && $request->user()->teacher) {
            $query->where('teacher_id', $request->user()->teacher->id);
        } elseif ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->integer('teacher_id'));
        }

        if ($request->filled('from')) {
            $query->whereDate('session_date', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('session_date', '<=', $request->date('to'));
        }

        return response()->json($query->latest('session_date')->paginate(50));
    }

    public function store(StoreWorkSessionRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (empty($data['teacher_id']) && $request->user()->teacher) {
            $data['teacher_id'] = $request->user()->teacher->id;
        }

        $session = WorkSession::create($data);

        return response()->json($session->load(['teacher.user', 'scheduleSlot']), 201);
    }

    public function show(WorkSession $workSession): JsonResponse
    {
        return response()->json($workSession->load(['teacher.user', 'scheduleSlot']));
    }

    public function update(StoreWorkSessionRequest $request, WorkSession $workSession): JsonResponse
    {
        $workSession->update($request->validated());

        return response()->json($workSession->fresh()->load(['teacher.user', 'scheduleSlot']));
    }

    public function destroy(WorkSession $workSession): JsonResponse
    {
        $workSession->delete();

        return response()->json(['message' => 'Запись часов удалена.']);
    }
}
