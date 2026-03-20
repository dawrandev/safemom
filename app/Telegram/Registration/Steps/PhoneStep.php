<?php

namespace App\Telegram\Registration\Steps;

use App\Services\ConversationService;
use App\Services\RegistrationService;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;

class PhoneStep
{
    /**
     * Handle phone step
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

        // Check if contact was shared
        $contact = $message->getContact();

        if (!$contact) {
            // User sent text instead of contact
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ Iltimos, quyidagi tugma orqali telefon raqamingizni ulashing:",
                'parse_mode' => 'HTML',
                'reply_markup' => $this->getContactKeyboard()
            ]);
            return;
        }

        // Verify that contact belongs to the user
        if ((string) $contact->getUserId() !== $telegramId) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ Iltimos, o'zingizning telefon raqamingizni ulashing:",
                'parse_mode' => 'HTML',
                'reply_markup' => $this->getContactKeyboard()
            ]);
            return;
        }

        $phone = $contact->getPhoneNumber();

        // Validate phone
        if (!$registrationService->validatePhone($phone)) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ Telefon raqam noto'g'ri.\n\nIltimos, qaytadan ulashing:",
                'parse_mode' => 'HTML',
                'reply_markup' => $this->getContactKeyboard()
            ]);
            return;
        }

        // Check if phone already registered
        if ($registrationService->phoneExists($phone)) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ Bu telefon raqam allaqachon ro'yxatdan o'tgan.\n\n/start buyrug'ini yuboring.",
                'parse_mode' => 'HTML',
                'reply_markup' => Keyboard::remove()
            ]);

            // Complete conversation
            $conversationService->completeConversation($telegramId);
            return;
        }

        // Get saved data
        $name = $conversationService->getData($telegramId, 'name');
        $surname = $conversationService->getData($telegramId, 'surname');

        // Create user
        try {
            $user = $registrationService->createUser([
                'name' => $name,
                'surname' => $surname,
                'phone' => $phone,
                'telegram_id' => $telegramId,
                'role' => 'patient'
            ]);

            // Complete conversation
            $conversationService->completeConversation($telegramId);

            // Show success message with Web App button
            $this->showSuccessMessage($chatId, $name);

        } catch (\Exception $e) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ Xatolik yuz berdi. Iltimos, qaytadan urinib ko'ring.\n\n/register",
                'parse_mode' => 'HTML',
                'reply_markup' => Keyboard::remove()
            ]);

            // Log error
            \Log::error('Registration error: ' . $e->getMessage());

            // Complete conversation
            $conversationService->completeConversation($telegramId);
        }
    }

    /**
     * Get contact request keyboard
     *
     * @return Keyboard
     */
    protected function getContactKeyboard()
    {
        return Keyboard::make([
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
    }

    /**
     * Show success message with Web App button
     *
     * @param int $chatId
     * @param string $name
     * @return void
     */
    protected function showSuccessMessage($chatId, $name)
    {
        $webAppUrl = env('TELEGRAM_WEB_APP_URL', 'https://your-webapp-url.com');

        $keyboard = Keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => '🚀 Launch App',
                        'web_app' => ['url' => $webAppUrl]
                    ]
                ]
            ]
        ]);

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "🎉 <b>Tabriklaymiz, {$name}!</b>\n\nSiz muvaffaqiyatli ro'yxatdan o'tdingiz!\n\nEndi ilovani ochish uchun quyidagi tugmani bosing:",
            'parse_mode' => 'HTML',
            'reply_markup' => $keyboard
        ]);

        // Remove keyboard
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => '.',
            'reply_markup' => Keyboard::remove()
        ]);

        // Delete the dot message immediately
        Telegram::deleteMessage([
            'chat_id' => $chatId,
            'message_id' => $chatId + 1
        ]);
    }
}
