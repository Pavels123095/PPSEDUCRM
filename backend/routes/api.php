<?php

use App\Http\Controllers\Api\ApplicantController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\IntegrationController;
use App\Http\Controllers\Api\ManagerDashboardController;
use App\Http\Controllers\Api\ReferenceController;
use App\Http\Controllers\Api\ScheduleSlotController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\WorkSessionController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('classrooms', [ReferenceController::class, 'classrooms']);
    Route::get('teachers-list', [ReferenceController::class, 'teachers']);
    Route::get('groups', [ReferenceController::class, 'groups']);

    Route::middleware('role:admin|manager')->group(function () {
        Route::get('managers/dashboard', [ManagerDashboardController::class, 'index']);
        Route::apiResource('applicants', ApplicantController::class);
        Route::patch('applicants/{applicant}/status', [ApplicantController::class, 'updateStatus']);
        Route::post('applicants/{applicant}/contracts', [ContractController::class, 'storeForApplicant']);
        Route::post('contracts/{contract}/sign', [ContractController::class, 'sign']);
        Route::get('contracts/{contract}', [ContractController::class, 'show']);
    });

    Route::middleware('role:admin|manager|teacher')->group(function () {
        Route::apiResource('schedule-slots', ScheduleSlotController::class);
        Route::apiResource('classrooms', ClassroomController::class)->except(['destroy', 'index']);
        Route::get('classrooms/availability', [ClassroomController::class, 'availability']);
        Route::apiResource('work-sessions', WorkSessionController::class);
        Route::get('teachers', [TeacherController::class, 'index']);
        Route::get('teachers/{teacher}', [TeacherController::class, 'show']);
        Route::get('teachers/{teacher}/hours-report', [TeacherController::class, 'hoursReport']);
    });

    Route::middleware('role:student')->prefix('student')->group(function () {
        Route::get('schedule', [StudentController::class, 'schedule']);
        Route::get('profile', [StudentController::class, 'profile']);
        Route::get('notifications', [StudentController::class, 'notifications']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::prefix('integrations/1c')->group(function () {
            Route::post('webhook', [IntegrationController::class, 'webhook']);
            Route::get('export/{entity}', [IntegrationController::class, 'export']);
            Route::post('import', [IntegrationController::class, 'import']);
        });
    });
});
