<?php

use App\Http\Controllers\TelegramController;
use App\Http\Controllers\VitalsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Telegram Webhook
Route::post('/telegram/webhook', [TelegramController::class, 'handle'])
    ->name('telegram.webhook');

// Vitals API (requires Telegram authentication)
Route::post('/vitals', [VitalsController::class, 'store'])
    ->middleware(['telegram.webapp', 'setlocale', 'auth.telegram'])
    ->name('api.vitals.store');
