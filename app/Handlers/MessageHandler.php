<?php

namespace App\Handlers;

use App\Services\ConversationService;
use App\Services\RegistrationService;
use App\Telegram\Registration\Steps\NameStep;
use App\Telegram\Registration\Steps\SurnameStep;
use App\Telegram\Registration\Steps\PhoneStep;
use Telegram\Bot\Laravel\Facades\Telegram;

class MessageHandler
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
     * Handle incoming messages
     *
     * @param \Telegram\Bot\Objects\Message $message
     * @return void
     */
    public function handle($message)
    {
        $text = $message->getText();
        $chatId = $message->getChat()->getId();
        $telegramId = (string) $message->getFrom()->getId();

        // Check for commands
        if ($text && str_starts_with($text, '/')) {
            // Commands are handled by Telegram::commandsHandler() in TelegramController
            return;
        }

        // Check for contact sharing
        if ($message->getContact()) {
            $this->handleContactShared($message);
            return;
        }

        // Check if user is in conversation
        if ($this->conversationService->isInConversation($telegramId)) {
            $this->handleConversationStep($message);
            return;
        }

        // Handle regular text messages
        $this->handleTextMessage($chatId, $text);
    }

    /**
     * Handle conversation steps
     *
     * @param \Telegram\Bot\Objects\Message $message
     * @return void
     */
    protected function handleConversationStep($message)
    {
        $telegramId = (string) $message->getFrom()->getId();
        $conversation = $this->conversationService->getConversation($telegramId);

        if (!$conversation) {
            return;
        }

        // Route to appropriate step handler
        match ($conversation->current_step) {
            'registration_name' => (new NameStep())->handle($message, $this->conversationService, $this->registrationService),
            'registration_surname' => (new SurnameStep())->handle($message, $this->conversationService, $this->registrationService),
            'registration_phone' => (new PhoneStep())->handle($message, $this->conversationService, $this->registrationService),
            default => null
        };
    }

    /**
     * Handle contact shared
     *
     * @param \Telegram\Bot\Objects\Message $message
     * @return void
     */
    protected function handleContactShared($message)
    {
        $telegramId = (string) $message->getFrom()->getId();
        $conversation = $this->conversationService->getConversation($telegramId);

        // Only process contact if user is in phone step
        if ($conversation && $conversation->current_step === 'registration_phone') {
            (new PhoneStep())->handle($message, $this->conversationService, $this->registrationService);
        }
    }

    /**
     * Handle text messages
     *
     * @param int $chatId
     * @param string $text
     * @return void
     */
    protected function handleTextMessage($chatId, $text)
    {
        // You can add more message handling logic here
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "Botdan foydalanish uchun /start buyrug'ini yuboring.",
        ]);
    }
}
