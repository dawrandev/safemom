<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class KickCount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'count',
        'duration_minutes',
        'session_start',
        'session_end',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'session_start' => 'datetime',
        'session_end' => 'datetime',
    ];

    /**
     * Get the user that owns the kick count session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active sessions.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('session_end');
    }

    /**
     * Scope a query to only include completed sessions.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->whereNotNull('session_end');
    }

    /**
     * Check if the session is still active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->session_end === null;
    }

    /**
     * Get the actual duration of the session in minutes.
     *
     * @return int|null
     */
    public function getActualDuration(): ?int
    {
        if ($this->session_end === null) {
            return null;
        }

        return $this->session_start->diffInMinutes($this->session_end);
    }

    /**
     * Check if the kick count goal (10 kicks) has been reached.
     *
     * @return bool
     */
    public function reachedGoal(): bool
    {
        return $this->count >= 10;
    }
}
