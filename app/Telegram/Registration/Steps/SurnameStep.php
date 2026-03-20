<?php

namespace App\Telegram\Registration\Steps;

use App\Services\ConversationService;
use App\Services\RegistrationService;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;

class SurnameStep
{
    /**
     * Handle surname step
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
        $surname = trim($message->getText());

        // Validate surname
        if (!$registrationService->validateName($surname)) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ Familiya noto'g'ri kiritildi.\n\nIltimos, to'liq familiyangizni kiriting (kamida 2 ta harf):",
                'parse_mode' => 'HTML'
            ]);
            return;
        }

        // Save surname to conversation data
        $conversationService->saveData($telegramId, 'surname', $surname);

        // Move to next step
        $conversationService->updateStep($telegramId, 'registration_phone');

        // Request phone number with contact button
        $keyboard = Keyboard::make([
            'keyboard' => [
                [
                    [
                        'text' => '📱 Telefon raqamni ulashish',
                        'request_contact' => true
                    ]
                ]
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "✅ Familiya saqlandi: <b>{$surname}</b>\n\nEndi telefon raqamingizni ulashing.\n\nQuyidagi tugmani bosib, telefon raqamingizni tasdiqlang:",
            'parse_mode' => 'HTML',
            'reply_markup' => $keyboard
        ]);
    }
}
