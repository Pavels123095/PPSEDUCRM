<?php

namespace App\Jobs;

use App\Models\IntegrationLog;
use App\Models\Student;
use App\Models\StudyGroup;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportStudentsFrom1C implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $records)
    {
    }

    public function handle(): void
    {
        IntegrationLog::create([
            'direction' => 'in',
            'entity_type' => Student::class,
            'entity_id' => null,
            'payload' => ['count' => count($this->records)],
            'status' => 'processing',
        ]);

        DB::transaction(function () {
            foreach ($this->records as $record) {
                $group = null;
                if (! empty($record['group_code'])) {
                    $group = StudyGroup::firstOrCreate(
                        ['code' => $record['group_code']],
                        [
                            'name' => $record['group_name'] ?? $record['group_code'],
                            'course' => $record['course'] ?? 1,
                            'specialty' => $record['specialty'] ?? null,
                        ]
                    );
                }

                $email = $record['email'] ?? Str::slug($record['external_id'] ?? Str::uuid()).'@import.local';

                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => trim(($record['last_name'] ?? '').' '.($record['first_name'] ?? '')),
                        'password' => Hash::make(Str::random(32)),
                    ]
                );

                if (! $user->hasRole('student')) {
                    $user->assignRole('student');
                }

                Student::updateOrCreate(
                    ['external_id' => $record['external_id']],
                    [
                        'user_id' => $user->id,
                        'study_group_id' => $group?->id,
                        'course' => $record['course'] ?? 1,
                        'sync_status' => 'synced',
                        'last_synced_at' => now(),
                        'compliance_metadata' => $record['compliance_metadata'] ?? null,
                    ]
                );
            }
        });

        IntegrationLog::create([
            'direction' => 'in',
            'entity_type' => Student::class,
            'entity_id' => null,
            'payload' => ['imported' => count($this->records)],
            'status' => 'success',
        ]);
    }
}
