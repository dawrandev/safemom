<?php

namespace App\Services;

use App\Handlers\CallbackQueryHandler;
use App\Handlers\MessageHandler;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramService
{
    public function handle($request)
    {
        try {
            $update = Telegram::getWebhookUpdate();

            if ($update->getCallbackQuery()) {
                app(CallbackQueryHandler::class)->handle($update->getCallbackQuery());
                return;
            }

            if ($update->getMessage()) {
                app(MessageHandler::class)->handle($update->getMessage());
                return;
            }
        } catch (\Exception $e) {
            Log::error('TelegramService error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
        }
    }
}
