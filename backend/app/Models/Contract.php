<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasUuids;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_SIGNED = 'signed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'applicant_id',
        'number',
        'template',
        'file_path',
        'status',
        'signed_at',
        'signed_by_manager_id',
        'external_id',
        'sync_status',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
            'last_synced_at' => 'datetime',
        ];
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function signedByManager(): BelongsTo
    {
        return $this->belongsTo(Manager::class, 'signed_by_manager_id');
    }
}
