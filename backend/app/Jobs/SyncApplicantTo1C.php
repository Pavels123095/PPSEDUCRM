<?php

namespace App\Jobs;

use App\Models\Applicant;
use App\Models\IntegrationLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncApplicantTo1C implements ShouldQueue
{
    use Queueable;

    public function __construct(public Applicant $applicant)
    {
    }

    public function handle(): void
    {
        if (! config('integrations.1c.enabled')) {
            $this->applicant->update(['sync_status' => 'skipped']);

            return;
        }

        $payload = [
            'external_id' => $this->applicant->external_id,
            'last_name' => $this->applicant->last_name,
            'first_name' => $this->applicant->first_name,
            'middle_name' => $this->applicant->middle_name,
            'snils' => $this->applicant->snils,
            'status' => $this->applicant->status,
            'email' => $this->applicant->email,
            'phone' => $this->applicant->phone,
        ];

        try {
            $response = Http::withToken(config('integrations.1c.api_key'))
                ->post(rtrim(config('integrations.1c.base_url'), '/').'/applicants', $payload);

            IntegrationLog::create([
                'direction' => 'out',
                'entity_type' => Applicant::class,
                'entity_id' => $this->applicant->id,
                'payload' => $payload,
                'status' => $response->successful() ? 'success' : 'failed',
                'error_message' => $response->successful() ? null : $response->body(),
            ]);

            if ($response->successful()) {
                $this->applicant->update([
                    'sync_status' => 'synced',
                    'last_synced_at' => now(),
                    'external_id' => $response->json('external_id') ?? $this->applicant->external_id,
                ]);
            } else {
                $this->applicant->update(['sync_status' => 'failed']);
            }
        } catch (\Throwable $e) {
            Log::error('SyncApplicantTo1C failed', ['error' => $e->getMessage()]);
            IntegrationLog::create([
                'direction' => 'out',
                'entity_type' => Applicant::class,
                'entity_id' => $this->applicant->id,
                'payload' => $payload,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            $this->applicant->update(['sync_status' => 'failed']);
        }
    }
}
