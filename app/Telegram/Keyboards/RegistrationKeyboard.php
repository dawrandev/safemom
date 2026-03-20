<?php

namespace App\Telegram\Keyboards;

use Telegram\Bot\Keyboard\Keyboard;

class RegistrationKeyboard
{
    /**
     * Get contact request keyboard
     *
     * @return Keyboard
     */
    public static function getContactRequestKeyboard(): Keyboard
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
     * Get Web App button
     *
     * @param string $url
     * @return Keyboard
     */
    public static function getWebAppButton(string $url = null): Keyboard
    {
        $webAppUrl = $url ?? env('TELEGRAM_WEB_APP_URL', 'https://your-webapp-url.com');

        return Keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => '🚀 Launch App',
                        'web_app' => ['url' => $webAppUrl]
                    ]
                ]
            ]
        ]);
    }

    /**
     * Get registration button
     *
     * @return Keyboard
     */
    public static function getRegistrationButton(): Keyboard
    {
        return Keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => '📝 Ro\'yxatdan o\'tish',
                        'callback_data' => 'start_registration'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Get cancel registration button
     *
     * @return Keyboard
     */
    public static function getCancelButton(): Keyboard
    {
        return Keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => '❌ Bekor qilish',
                        'callback_data' => 'cancel_registration'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Remove keyboard
     *
     * @return Keyboard
     */
    public static function remove(): Keyboard
    {
        return Keyboard::remove();
    }
}
