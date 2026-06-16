<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\StudyGroup;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;

class ReferenceController extends Controller
{
    public function classrooms(): JsonResponse
    {
        return response()->json(
            Classroom::query()->where('is_active', true)->orderBy('building')->orderBy('number')->get()
        );
    }

    public function teachers(): JsonResponse
    {
        return response()->json(
            Teacher::query()->with('user')->get()
        );
    }

    public function groups(): JsonResponse
    {
        return response()->json(
            StudyGroup::query()->orderBy('name')->get()
        );
    }
}
