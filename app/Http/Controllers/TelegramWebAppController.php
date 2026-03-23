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

        // Fetch user data from database using telegram_id
        $user = User::where('telegram_id', $telegramUser['id'])->first();

        // Get latest AI diagnosis if user exists
        $latestDiagnosis = null;
        if ($user) {
            $latestDiagnosis = $user->aiDiagnoses()
                ->with('vital')
                ->latest()
                ->first();
        }

        // Get profile and medications
        $profile = $user?->profile;
        $medications = $user?->medications()->active()->current()->get() ?? collect();

        // Calculate pregnancy data from profile
        $pregnancyWeek = $profile?->getPregnancyWeek() ?? 0;

        $userData = [
            'name' => $user?->name ?? $telegramUser['first_name'],
            'week' => $pregnancyWeek,
            'trimester' => $profile?->getTrimester() ?? 1,
            'baby_size' => $this->getBabySizeForWeek($pregnancyWeek),
            'baby_weight' => $this->getBabyWeightForWeek($pregnancyWeek),
        ];

        return view('telegram_bot.dashboard', [
            'telegram_user' => $telegramUser,
            'user' => $userData,
            'latest_diagnosis' => $latestDiagnosis,
            'medications' => $medications,
        ]);
    }

    /**
     * Get baby size for a given pregnancy week
     */
    private function getBabySizeForWeek(int $week): string
    {
        $sizes = [
            4 => 'poppy seed',
            5 => 'sesame seed',
            6 => 'lentil',
            7 => 'blueberry',
            8 => 'kidney bean',
            9 => 'grape',
            10 => 'kumquat',
            11 => 'fig',
            12 => 'lime',
            13 => 'pea pod',
            14 => 'lemon',
            15 => 'apple',
            16 => 'avocado',
            17 => 'pear',
            18 => 'bell pepper',
            19 => 'heirloom tomato',
            20 => 'banana',
            21 => 'carrot',
            22 => 'spaghetti squash',
            23 => 'grapefruit',
            24 => 'ear of corn',
            25 => 'cauliflower',
            26 => 'lettuce',
            27 => 'rutabaga',
            28 => 'eggplant',
            29 => 'butternut squash',
            30 => 'cabbage',
            31 => 'coconut',
            32 => 'jicama',
            33 => 'pineapple',
            34 => 'cantaloupe',
            35 => 'honeydew melon',
            36 => 'romaine lettuce',
            37 => 'swiss chard',
            38 => 'leek',
            39 => 'mini watermelon',
            40 => 'small pumpkin',
        ];

        if ($week <= 3) {
            return 'tiny seed';
        }

        if ($week >= 40) {
            return 'watermelon';
        }

        return $sizes[$week] ?? 'growing baby';
    }

    /**
     * Get baby weight for a given pregnancy week
     */
    private function getBabyWeightForWeek(int $week): string
    {
        $weights = [
            8 => '0.04 oz',
            9 => '0.07 oz',
            10 => '0.14 oz',
            11 => '0.25 oz',
            12 => '0.49 oz',
            13 => '0.81 oz',
            14 => '1.52 oz',
            15 => '2.47 oz',
            16 => '3.53 oz',
            17 => '4.94 oz',
            18 => '6.70 oz',
            19 => '8.47 oz',
            20 => '10.58 oz',
            21 => '12.70 oz',
            22 => '15.17 oz',
            23 => '1.1 lbs',
            24 => '1.3 lbs',
            25 => '1.5 lbs',
            26 => '1.7 lbs',
            27 => '2.0 lbs',
            28 => '2.2 lbs',
            29 => '2.5 lbs',
            30 => '2.9 lbs',
            31 => '3.3 lbs',
            32 => '3.7 lbs',
            33 => '4.2 lbs',
            34 => '4.7 lbs',
            35 => '5.3 lbs',
            36 => '5.8 lbs',
            37 => '6.3 lbs',
            38 => '6.8 lbs',
            39 => '7.3 lbs',
            40 => '7.6 lbs',
        ];

        if ($week < 8) {
            return 'less than 0.04 oz';
        }

        if ($week >= 40) {
            return '7-8 lbs';
        }

        return $weights[$week] ?? 'growing';
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

    /**
     * Set application language
     */
    public function setLanguage($locale)
    {
        if (in_array($locale, ['uz', 'ru', 'en'])) {
            session(['locale' => $locale]);
        }
        return redirect()->back();
    }
}
