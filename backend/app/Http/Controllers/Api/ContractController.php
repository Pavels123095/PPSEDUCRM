<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contract\SignContractRequest;
use App\Http\Requests\Contract\StoreContractRequest;
use App\Models\Applicant;
use App\Models\Contract;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    public function __construct(private AuditService $auditService)
    {
    }

    public function storeForApplicant(StoreContractRequest $request, Applicant $applicant): JsonResponse
    {
        $contract = $applicant->contracts()->create([
            ...$request->validated(),
            'status' => Contract::STATUS_DRAFT,
        ]);

        $applicant->update(['status' => Applicant::STATUS_CONTRACT_DRAFT]);

        $this->auditService->log('contract.created', $contract, null, $contract->toArray());

        return response()->json($contract, 201);
    }

    public function show(Contract $contract): JsonResponse
    {
        return response()->json($contract->load(['applicant', 'signedByManager.user']));
    }

    public function sign(SignContractRequest $request, Contract $contract): JsonResponse
    {
        $data = ['signed_at' => $request->validated('signed_at'), 'status' => Contract::STATUS_SIGNED];

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('', 'contracts');
            $data['file_path'] = $path;
        }

        $manager = Auth::user()?->manager;
        if ($manager) {
            $data['signed_by_manager_id'] = $manager->id;
        }

        $old = $contract->toArray();
        $contract->update($data);

        $contract->applicant?->update(['status' => Applicant::STATUS_CONTRACT_SIGNED]);

        $this->auditService->log('contract.signed', $contract, $old, $contract->fresh()->toArray());

        return response()->json($contract->fresh()->load('applicant'));
    }
}
