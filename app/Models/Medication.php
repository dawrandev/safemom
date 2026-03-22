<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Medication extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'dosage',
        'time_to_take',
        'start_date',
        'end_date',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the medication.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the medication logs for this medication.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(MedicationLog::class);
    }

    /**
     * Scope a query to only include active medications.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include current medications (within date range).
     */
    public function scopeCurrent(Builder $query): Builder
    {
        $today = now()->toDateString();
        return $query->where('start_date', '<=', $today)
                     ->where(function ($q) use ($today) {
                         $q->whereNull('end_date')
                           ->orWhere('end_date', '>=', $today);
                     });
    }

    /**
     * Check if the medication is currently active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $today = now()->toDateString();
        $afterStart = $this->start_date <= $today;
        $beforeEnd = $this->end_date === null || $this->end_date >= $today;

        return $afterStart && $beforeEnd;
    }

    /**
     * Parse time_to_take into an array of times.
     *
     * @return array
     */
    public function getDailySchedule(): array
    {
        return array_map('trim', explode(',', $this->time_to_take));
    }

    /**
     * Get today's medication log entry.
     *
     * @return MedicationLog|null
     */
    public function getTodayLog(): ?MedicationLog
    {
        return $this->logs()
                    ->whereDate('taken_at', now()->toDateString())
                    ->first();
    }
}
