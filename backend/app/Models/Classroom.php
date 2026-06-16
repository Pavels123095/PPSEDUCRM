<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    protected $fillable = [
        'number',
        'building',
        'capacity',
        'equipment',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'equipment' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function scheduleSlots(): HasMany
    {
        return $this->hasMany(ScheduleSlot::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->building
            ? "{$this->building}, ауд. {$this->number}"
            : "Ауд. {$this->number}";
    }
}
