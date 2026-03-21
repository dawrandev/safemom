<?php

namespace App\Telegram\Commands;

use App\Services\RegistrationService;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;

class StartCommand extends Command
{
    /**
     * Command name
     *
     * @var string
     */
    protected string $name = 'start';

    /**
     * Command description
     *
     * @var string
     */
    protected string $description = 'Start buyrug\'i - Botni ishga tushirish';

    /**
     * @var RegistrationService
     */
    protected RegistrationService $registrationService;

    /**
     * Constructor
     */
    public function __construct()
    {
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
        $firstName = $this->getUpdate()->getMessage()->getFrom()->getFirstName();

        // Show Web App button directly (no registration needed)
        $this->showWebAppButton($chatId, $firstName);
    }

    /**
     * Show Web App button for registered users
     *
     * @param int $chatId
     * @param string $firstName
     * @return void
     */
    protected function showWebAppButton($chatId, $firstName)
    {
        $webAppUrl = config('telegram.bots.mybot.web_app_url');

        // If not configured, use default
        if (!$webAppUrl) {
            $webAppUrl = config('app.url') . '/telegram/webapp/dashboard';
        }

        \Log::info('Web App URL: ' . $webAppUrl);

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
            'text' => "Salom, {$firstName}! 👋\n\n<b>SafeMom</b> botiga xush kelibsiz!\n\nIlovani ochish uchun quyidagi tugmani bosing:",
            'parse_mode' => 'HTML',
            'reply_markup' => $keyboard
        ]);
    }

    /**
     * Show registration button for new users
     *
     * @param int $chatId
     * @param string $firstName
     * @return void
     */
    protected function showRegistrationButton($chatId, $firstName)
    {
        $keyboard = Keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => '📝 Ro\'yxatdan o\'tish',
                        'callback_data' => 'start_registration'
                    ]
                ]
            ]
        ]);

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "Salom, {$firstName}! 👋\n\n<b>SafeMom</b> botiga xush kelibsiz!\n\nDavom etish uchun ro'yxatdan o'ting:",
            'parse_mode' => 'HTML',
            'reply_markup' => $keyboard
        ]);
    }
}
