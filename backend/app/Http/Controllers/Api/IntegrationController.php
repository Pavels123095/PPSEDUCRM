<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Integration\Import1CRequest;
use App\Jobs\ImportStudentsFrom1C;
use App\Models\Applicant;
use App\Models\Contract;
use App\Models\IntegrationLog;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function webhook(Request $request): JsonResponse
    {
        $secret = config('integrations.1c.webhook_secret');

        if ($secret && $request->header('X-1C-Signature') !== hash_hmac('sha256', $request->getContent(), $secret)) {
            return response()->json(['message' => 'Неверная подпись.'], 401);
        }

        IntegrationLog::create([
            'direction' => 'in',
            'entity_type' => $request->input('entity_type'),
            'entity_id' => $request->input('entity_id'),
            'payload' => $request->all(),
            'status' => 'received',
        ]);

        return response()->json(['message' => 'Webhook принят.', 'status' => 'ok']);
    }

    public function export(string $entity): JsonResponse
    {
        $allowed = config('integrations.1c.sync_entities', []);

        if (! in_array($entity, $allowed, true)) {
            return response()->json(['message' => 'Неизвестная сущность.'], 404);
        }

        $data = match ($entity) {
            'applicants' => Applicant::query()->get(),
            'students' => Student::query()->with('user', 'studyGroup')->get(),
            'contracts' => Contract::query()->with('applicant')->get(),
            'teachers' => Teacher::query()->with('user')->get(),
            default => collect(),
        };

        IntegrationLog::create([
            'direction' => 'out',
            'entity_type' => $entity,
            'entity_id' => null,
            'payload' => ['count' => $data->count()],
            'status' => 'success',
        ]);

        return response()->json([
            'entity' => $entity,
            'exported_at' => now()->toIso8601String(),
            'data' => $data,
        ]);
    }

    public function import(Import1CRequest $request): JsonResponse
    {
        $entity = $request->validated('entity');
        $records = $request->validated('records');

        if ($entity === 'students') {
            ImportStudentsFrom1C::dispatch($records);

            return response()->json([
                'message' => 'Импорт студентов поставлен в очередь.',
                'count' => count($records),
            ]);
        }

        IntegrationLog::create([
            'direction' => 'in',
            'entity_type' => $entity,
            'entity_id' => null,
            'payload' => ['count' => count($records), 'stub' => true],
            'status' => 'skipped',
        ]);

        return response()->json([
            'message' => "Импорт {$entity} зарегистрирован (stub).",
            'count' => count($records),
        ]);
    }
}
