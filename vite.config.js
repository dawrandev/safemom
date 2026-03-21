import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Main app assets
                'resources/css/app.css',
                'resources/js/app.js',

                // Telegram bot shared assets
                'resources/css/telegram_bot/base.css',
                'resources/css/telegram_bot/components.css',
                'resources/js/telegram_bot/telegram-init.js',
                'resources/js/telegram_bot/utils.js',

                // Telegram bot page-specific assets
                'resources/css/telegram_bot/dashboard.css',
                'resources/js/telegram_bot/dashboard.js',
                'resources/css/telegram_bot/vitals.css',
                'resources/js/telegram_bot/vitals.js',
                'resources/css/telegram_bot/monitoring.css',
                'resources/js/telegram_bot/monitoring.js',
                'resources/css/telegram_bot/health_trend.css',
                'resources/js/telegram_bot/health_trend.js',
                'resources/css/telegram_bot/community_support.css',
                'resources/js/telegram_bot/community_support.js',
            ],
            refresh: true,
        }),
    ],
});
