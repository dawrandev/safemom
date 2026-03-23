<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show onboarding page
     */
    public function onboarding(Request $request)
    {
        $telegramUser = $request->attributes->get('telegram_user', [
            'id' => 0,
            'first_name' => 'Guest',
            'last_name' => '',
            'username' => 'guest'
        ]);

        return view('telegram_bot.onboarding', [
            'telegram_user' => $telegramUser,
        ]);
    }

    /**
     * Show profile page
     */
    public function show(Request $request)
    {
        $telegramUser = $request->attributes->get('telegram_user', [
            'id' => 0,
            'first_name' => 'Guest',
            'last_name' => '',
            'username' => 'guest'
        ]);

        $user = User::where('telegram_id', $telegramUser['id'])->first();
        $profile = $user?->profile;
        $medications = $user?->medications()->get() ?? collect();

        return view('telegram_bot.profile', [
            'telegram_user' => $telegramUser,
            'user' => $user,
            'profile' => $profile,
            'medications' => $medications,
        ]);
    }

    /**
     * Store or update profile data
     */
    public function store(StoreProfileRequest $request)
    {
        $telegramUser = $request->attributes->get('telegram_user', []);

        $user = User::where('telegram_id', $telegramUser['id'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Update user basic info
        $user->update([
            'name' => $request->name,
            'surname' => $request->surname,
        ]);

        // Create or update profile
        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'age' => $request->age,
                'height' => $request->height,
                'lmp_date' => $request->lmp_date,
                'current_weight' => $request->weight,
                'blood_type' => $request->blood_type ?? 'unknown',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Profile saved successfully',
            'data' => [
                'pregnancy_week' => $profile->getPregnancyWeek(),
                'trimester' => $profile->getTrimester(),
                'redirect_url' => route('telegram.webapp.dashboard'),
            ],
        ]);
    }
}
