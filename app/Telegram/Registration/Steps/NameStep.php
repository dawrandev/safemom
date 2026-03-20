<?php

namespace App\Telegram\Registration\Steps;

use App\Services\ConversationService;
use App\Services\RegistrationService;
use Telegram\Bot\Laravel\Facades\Telegram;

class NameStep
{
    /**
     * Handle name step
     *
     * @param \Telegram\Bot\Objects\Message $message
     * @param ConversationService $conversationService
     * @param RegistrationService $registrationService
     * @return void
     */
    public function handle($message, ConversationService $conversationService, RegistrationService $registrationService)
    {
        $chatId = $message->getChat()->getId();
        $telegramId = (string) $message->getFrom()->getId();
        $name = trim($message->getText());

        // Validate name
        if (!$registrationService->validateName($name)) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ Ism noto'g'ri kiritildi.\n\nIltimos, to'liq ismingizni kiriting (kamida 2 ta harf):",
                'parse_mode' => 'HTML'
            ]);
            return;
        }

        // Save name to conversation data
        $conversationService->saveData($telegramId, 'name', $name);

        // Move to next step
        $conversationService->updateStep($telegramId, 'registration_surname');

        // Ask for surname
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "✅ Ism saqlandi: <b>{$name}</b>\n\nEndi familiyangizni kiriting:",
            'parse_mode' => 'HTML'
        ]);
    }
}
