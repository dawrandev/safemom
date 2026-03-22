<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vital extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'systolic_bp',
        'diastolic_bp',
        'heart_rate',
        'temperature',
        'glucose_level',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'temperature' => 'decimal:1',
        'glucose_level' => 'decimal:2',
    ];

    /**
     * Get the user that owns the vital sign record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the AI diagnosis for this vital sign record.
     */
    public function aiDiagnosis(): HasOne
    {
        return $this->hasOne(AiDiagnosis::class, 'vitals_id');
    }

    /**
     * Check if all vitals are within normal range.
     *
     * @return bool
     */
    public function isNormal(): bool
    {
        return $this->getBloodPressureStatus() === 'normal' &&
               $this->heart_rate >= 60 && $this->heart_rate <= 100 &&
               $this->temperature >= 36.1 && $this->temperature <= 37.2;
    }

    /**
     * Get blood pressure status.
     *
     * @return string
     */
    public function getBloodPressureStatus(): string
    {
        if ($this->systolic_bp < 90 || $this->diastolic_bp < 60) {
            return 'low';
        } elseif ($this->systolic_bp > 140 || $this->diastolic_bp > 90) {
            return 'high';
        }
        return 'normal';
    }
}
