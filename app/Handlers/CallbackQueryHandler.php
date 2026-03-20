<?php

namespace App\Handlers;

use App\Services\ConversationService;
use App\Services\RegistrationService;
use Telegram\Bot\Laravel\Facades\Telegram;

class CallbackQueryHandler
{
    /**
     * @var ConversationService
     */
    protected ConversationService $conversationService;

    /**
     * @var RegistrationService
     */
    protected RegistrationService $registrationService;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->conversationService = new ConversationService();
        $this->registrationService = new RegistrationService();
    }

    /**
     * Handle callback query
     *
     * @param \Telegram\Bot\Objects\CallbackQuery $callbackQuery
     * @return void
     */
    public function handle($callbackQuery)
    {
        $data = $callbackQuery->getData();
        $chatId = $callbackQuery->getMessage()->getChat()->getId();
        $messageId = $callbackQuery->getMessage()->getMessageId();
        $telegramId = (string) $callbackQuery->getFrom()->getId();

        // Answer callback query to remove loading state
        Telegram::answerCallbackQuery([
            'callback_query_id' => $callbackQuery->getId()
        ]);

        // Route to appropriate handler
        match($data) {
            'start_registration' => $this->startRegistration($chatId, $messageId, $telegramId),
            'cancel_registration' => $this->cancelRegistration($chatId, $messageId, $telegramId),
            default => $this->handleUnknown($chatId)
        };
    }

    /**
     * Start registration process
     *
     * @param int $chatId
     * @param int $messageId
     * @param string $telegramId
     * @return void
     */
    protected function startRegistration($chatId, $messageId, $telegramId)
    {
        // Check if already registered
        if ($this->registrationService->isRegistered($telegramId)) {
            Telegram::editMessageText([
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => "❌ Siz allaqachon ro'yxatdan o'tgansiz!",
                'parse_mode' => 'HTML'
            ]);
            return;
        }

        // Start conversation
        $this->conversationService->startConversation($telegramId, 'registration_name');

        // Edit message and ask for name
        Telegram::editMessageText([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => "📝 <b>Ro'yxatdan o'tish</b>\n\nIltimos, ismingizni kiriting:",
            'parse_mode' => 'HTML'
        ]);
    }

    /**
     * Cancel registration
     *
     * @param int $chatId
     * @param int $messageId
     * @param string $telegramId
     * @return void
     */
    protected function cancelRegistration($chatId, $messageId, $telegramId)
    {
        $this->conversationService->completeConversation($telegramId);

        Telegram::editMessageText([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => "❌ Ro'yxatdan o'tish bekor qilindi.\n\nQaytadan boshlash uchun /start yuboring.",
            'parse_mode' => 'HTML'
        ]);
    }

    /**
     * Handle unknown callback
     *
     * @param int $chatId
     * @return void
     */
    protected function handleUnknown($chatId)
    {
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "Noma'lum buyruq. /start yuboring.",
        ]);
    }
}
