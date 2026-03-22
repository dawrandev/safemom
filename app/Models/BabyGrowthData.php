<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BabyGrowthData extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'week_number',
        'size_comparison',
        'development_description',
        'image_url',
    ];

    /**
     * Scope a query to get data for a specific week.
     */
    public function scopeForWeek(Builder $query, int $weekNumber): Builder
    {
        return $query->where('week_number', $weekNumber);
    }

    /**
     * Get growth data for the user's current pregnancy week.
     *
     * @param User $user
     * @return BabyGrowthData|null
     */
    public static function getCurrentWeekData(User $user): ?BabyGrowthData
    {
        if (!$user->profile) {
            return null;
        }

        $currentWeek = $user->profile->getPregnancyWeek();
        return self::forWeek($currentWeek)->first();
    }
}
