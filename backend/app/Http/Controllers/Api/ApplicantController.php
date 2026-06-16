<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Applicant\StoreApplicantRequest;
use App\Http\Requests\Applicant\UpdateApplicantRequest;
use App\Http\Requests\Applicant\UpdateApplicantStatusRequest;
use App\Jobs\SyncApplicantTo1C;
use App\Models\Applicant;
use App\Services\AuditService;
use App\Services\SnilsValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function __construct(
        private AuditService $auditService,
        private SnilsValidator $snilsValidator,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $query = Applicant::query()->with(['manager.user', 'contracts']);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('manager_id')) {
            $query->where('manager_id', $request->integer('manager_id'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function store(StoreApplicantRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (! empty($data['snils'])) {
            $data['snils'] = $this->snilsValidator->format($data['snils']);
        }

        $applicant = Applicant::create($data);

        $this->auditService->log('applicant.created', $applicant, null, $applicant->toArray());
        SyncApplicantTo1C::dispatch($applicant);

        return response()->json($applicant->load('manager.user'), 201);
    }

    public function show(Applicant $applicant): JsonResponse
    {
        return response()->json($applicant->load(['manager.user', 'contracts', 'student']));
    }

    public function update(UpdateApplicantRequest $request, Applicant $applicant): JsonResponse
    {
        $old = $applicant->toArray();
        $data = $request->validated();

        if (! empty($data['snils'])) {
            $data['snils'] = $this->snilsValidator->format($data['snils']);
        }

        $applicant->update($data);
        $this->auditService->log('applicant.updated', $applicant, $old, $applicant->fresh()->toArray());
        SyncApplicantTo1C::dispatch($applicant->fresh());

        return response()->json($applicant->fresh()->load('manager.user'));
    }

    public function destroy(Applicant $applicant): JsonResponse
    {
        $this->auditService->log('applicant.deleted', $applicant, $applicant->toArray());
        $applicant->delete();

        return response()->json(['message' => 'Абитуриент удалён.']);
    }

    public function updateStatus(UpdateApplicantStatusRequest $request, Applicant $applicant): JsonResponse
    {
        $oldStatus = $applicant->status;
        $applicant->update(['status' => $request->validated('status')]);

        $this->auditService->log(
            'applicant.status_changed',
            $applicant,
            ['status' => $oldStatus],
            ['status' => $applicant->status],
        );

        SyncApplicantTo1C::dispatch($applicant->fresh());

        return response()->json($applicant->fresh());
    }
}
