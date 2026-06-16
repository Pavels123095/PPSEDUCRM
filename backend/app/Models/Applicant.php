<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Applicant extends Model
{
    use HasUuids;

    public const STATUS_NEW = 'new';
    public const STATUS_CONTACTED = 'contacted';
    public const STATUS_CONTRACT_DRAFT = 'contract_draft';
    public const STATUS_CONTRACT_SIGNED = 'contract_signed';
    public const STATUS_ENROLLED = 'enrolled';
    public const STATUS_REJECTED = 'rejected';

    public const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_CONTACTED,
        self::STATUS_CONTRACT_DRAFT,
        self::STATUS_CONTRACT_SIGNED,
        self::STATUS_ENROLLED,
        self::STATUS_REJECTED,
    ];

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'email',
        'phone',
        'snils',
        'passport_series',
        'passport_number',
        'status',
        'manager_id',
        'student_id',
        'external_id',
        'sync_status',
        'last_synced_at',
        'compliance_metadata',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'compliance_metadata' => 'array',
            'last_synced_at' => 'datetime',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->last_name} {$this->first_name} {$this->middle_name}");
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
