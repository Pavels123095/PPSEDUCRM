<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Http\JsonResponse;

class ManagerDashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $byStatus = Applicant::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recent = Applicant::query()
            ->with('manager.user')
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'funnel' => collect(Applicant::STATUSES)->mapWithKeys(fn (string $status) => [
                $status => (int) ($byStatus[$status] ?? 0),
            ]),
            'total' => Applicant::count(),
            'recent_applicants' => $recent,
        ]);
    }
}
