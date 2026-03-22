<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'age',
        'lmp_date',
        'current_weight',
        'blood_type',
        'medical_history',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'lmp_date' => 'date',
        'current_weight' => 'decimal:2',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate pregnancy week from LMP date
     *
     * @return int
     */
    public function getPregnancyWeek(): int
    {
        $now = now();
        $daysSinceLmp = $this->lmp_date->diffInDays($now);
        return (int) floor($daysSinceLmp / 7);
    }

    /**
     * Get trimester based on pregnancy week
     *
     * @return int
     */
    public function getTrimester(): int
    {
        $week = $this->getPregnancyWeek();

        if ($week <= 13) {
            return 1;
        } elseif ($week <= 26) {
            return 2;
        } else {
            return 3;
        }
    }
}
