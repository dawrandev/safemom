<?php

namespace App\Telegram\Commands;

use App\Services\ConversationService;
use App\Services\RegistrationService;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class RegisterCommand extends Command
{
    /**
     * Command name
     *
     * @var string
     */
    protected string $name = 'register';

    /**
     * Command description
     *
     * @var string
     */
    protected string $description = 'Ro\'yxatdan o\'tish';

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
     * Command handler
     *
     * @return void
     */
    public function handle()
    {
        $chatId = $this->getUpdate()->getMessage()->getChat()->getId();
        $telegramId = (string) $this->getUpdate()->getMessage()->getFrom()->getId();

        // Check if user is already registered
        if ($this->registrationService->isRegistered($telegramId)) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ Siz allaqachon ro'yxatdan o'tgansiz!\n\n/start buyrug'ini yuboring.",
                'parse_mode' => 'HTML'
            ]);
            return;
        }

        // Start registration conversation
        $this->conversationService->startConversation($telegramId, 'registration_name');

        // Ask for name
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "📝 <b>Ro'yxatdan o'tish</b>\n\nIltimos, ismingizni kiriting:",
            'parse_mode' => 'HTML'
        ]);
    }
}
