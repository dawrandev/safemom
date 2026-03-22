<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class AiDiagnosis extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'vitals_id',
        'status',
        'analysis_text',
        'nutrition_advice',
        'is_critical',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_critical' => 'boolean',
    ];

    /**
     * Get the user that owns the diagnosis.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vital sign record associated with this diagnosis.
     */
    public function vital(): BelongsTo
    {
        return $this->belongsTo(Vital::class, 'vitals_id');
    }

    /**
     * Scope a query to only include critical diagnoses.
     */
    public function scopeCritical(Builder $query): Builder
    {
        return $query->where('is_critical', true);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Check if the diagnosis is critical.
     *
     * @return bool
     */
    public function isCritical(): bool
    {
        return $this->is_critical;
    }

    /**
     * Check if the diagnosis requires action.
     *
     * @return bool
     */
    public function requiresAction(): bool
    {
        return in_array($this->status, ['yellow', 'red']);
    }
}
