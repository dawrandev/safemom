<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Language switching route
Route::get('/lang/{locale}', [App\Http\Controllers\TelegramWebAppController::class, 'setLanguage'])->name('setLanguage');

// Telegram Web App Routes
Route::prefix('telegram/webapp')->middleware('setlocale')->group(function () {
    // Onboarding - without authentication
    Route::get('/onboarding', [App\Http\Controllers\ProfileController::class, 'onboarding'])
        ->name('telegram.webapp.onboarding');

    // Profile store - with auth but without profile check
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'store'])
        ->middleware(['telegram.webapp', 'auth.telegram'])
        ->name('telegram.webapp.profile.store');

    // Main pages - with profile completion check
    Route::middleware(['telegram.webapp', 'auth.telegram', 'profile.complete'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\TelegramWebAppController::class, 'dashboard'])->name('telegram.webapp.dashboard');
        Route::get('/vitals', [App\Http\Controllers\TelegramWebAppController::class, 'vitals'])->name('telegram.webapp.vitals');
        Route::get('/monitoring', [App\Http\Controllers\TelegramWebAppController::class, 'monitoring'])->name('telegram.webapp.monitoring');
        Route::get('/health-trend', [App\Http\Controllers\TelegramWebAppController::class, 'healthTrend'])->name('telegram.webapp.health_trend');
        Route::get('/community-support', [App\Http\Controllers\TelegramWebAppController::class, 'communitySupport'])->name('telegram.webapp.community_support');

        // Profile page
        Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('telegram.webapp.profile');

        // Medications
        Route::post('/medications', [App\Http\Controllers\MedicationController::class, 'store'])->name('telegram.webapp.medications.store');
        Route::delete('/medications/{medication}', [App\Http\Controllers\MedicationController::class, 'destroy'])->name('telegram.webapp.medications.destroy');
    });

    // Test route without authentication (for development only)
    Route::get('/test', function () {
        return view('telegram_bot.dashboard', [
            'telegram_user' => [
                'id' => 123456789,
                'first_name' => 'Test',
                'last_name' => 'User',
                'username' => 'testuser'
            ],
            'user' => [
                'name' => 'Sarah',
                'week' => 24,
                'trimester' => 2,
                'baby_size' => 'ear of corn',
                'baby_weight' => '1.3 pounds',
            ]
        ]);
    })->name('telegram.webapp.test');

    // Test bot command
    Route::get('/test-bot', function () {
        $telegram = Telegram\Bot\Laravel\Facades\Telegram::bot('mybot');

        // Get bot info
        $me = $telegram->getMe();

        return response()->json([
            'bot' => $me,
            'web_app_url' => config('telegram.bots.mybot.web_app_url'),
        ]);
    });
});
