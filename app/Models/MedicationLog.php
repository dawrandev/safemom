<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class MedicationLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'medication_id',
        'taken_at',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'taken_at' => 'datetime',
    ];

    /**
     * Get the medication that this log belongs to.
     */
    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }

    /**
     * Scope a query to only include taken medication logs.
     */
    public function scopeTaken(Builder $query): Builder
    {
        return $query->where('status', 'taken');
    }

    /**
     * Scope a query to only include skipped medication logs.
     */
    public function scopeSkipped(Builder $query): Builder
    {
        return $query->where('status', 'skipped');
    }

    /**
     * Scope a query to only include today's logs.
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('taken_at', now()->toDateString());
    }
}
