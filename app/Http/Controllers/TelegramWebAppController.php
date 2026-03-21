<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class TelegramWebAppController extends Controller
{
    /**
     * Show dashboard page
     */
    public function dashboard(Request $request)
    {
        $telegramUser = $request->attributes->get('telegram_user', [
            'id' => 0,
            'first_name' => 'Guest',
            'last_name' => '',
            'username' => 'guest'
        ]);

        // You can fetch user data from database using telegram_id
        // $user = User::where('telegram_id', $telegramUser['id'])->first();

        $userData = [
            'name' => $telegramUser['first_name'] ?? 'Guest',
            'week' => 24,
            'trimester' => 2,
            'baby_size' => 'ear of corn',
            'baby_weight' => '1.3 pounds',
        ];

        return view('telegram_bot.dashboard', [
            'telegram_user' => $telegramUser,
            'user' => $userData,
        ]);
    }

    /**
     * Show vitals page
     */
    public function vitals(Request $request)
    {
        $telegramUser = $request->attributes->get('telegram_user', [
            'id' => 0,
            'first_name' => 'Guest',
            'last_name' => '',
            'username' => 'guest'
        ]);

        return view('telegram_bot.vitals', [
            'telegram_user' => $telegramUser,
        ]);
    }

    /**
     * Show monitoring page
     */
    public function monitoring(Request $request)
    {
        $telegramUser = $request->attributes->get('telegram_user', [
            'id' => 0,
            'first_name' => 'Guest',
            'last_name' => '',
            'username' => 'guest'
        ]);

        return view('telegram_bot.monitoring', [
            'telegram_user' => $telegramUser,
        ]);
    }

    /**
     * Show health trend page
     */
    public function healthTrend(Request $request)
    {
        $telegramUser = $request->attributes->get('telegram_user', [
            'id' => 0,
            'first_name' => 'Guest',
            'last_name' => '',
            'username' => 'guest'
        ]);

        return view('telegram_bot.health_trend', [
            'telegram_user' => $telegramUser,
        ]);
    }

    /**
     * Show community support page
     */
    public function communitySupport(Request $request)
    {
        $telegramUser = $request->attributes->get('telegram_user', [
            'id' => 0,
            'first_name' => 'Guest',
            'last_name' => '',
            'username' => 'guest'
        ]);

        return view('telegram_bot.community_support', [
            'telegram_user' => $telegramUser,
        ]);
    }
}
