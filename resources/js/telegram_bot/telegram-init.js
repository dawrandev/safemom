/**
 * Initialize Telegram Web App using native SDK
 * @returns {Object} Telegram Web App instance
 */
export function initTelegramWebApp() {
    // Use native Telegram Web App SDK
    const tg = window.Telegram?.WebApp;

    if (!tg) {
        console.warn('Telegram WebApp SDK not found. Make sure you opened this page from Telegram.');
        return null;
    }

    try {
        // Set colors to match app theme
        tg.setHeaderColor('#fdfbf7');
        tg.setBackgroundColor('#fdfbf7');

        // Log initData for debugging (remove in production)
        if (tg.initData) {
            console.log('Telegram WebApp initialized successfully');
        }

        return tg;
    } catch (error) {
        console.error('Failed to initialize Telegram Web App:', error);
        return null;
    }
}

/**
 * Setup back button with custom handler
 * @param {Function} onBackClick - Callback function when back button is clicked
 */
export function setupBackButton(onBackClick) {
    const tg = window.Telegram?.WebApp;

    if (!tg) return null;

    try {
        tg.BackButton.show();
        tg.BackButton.onClick(onBackClick || (() => {
            window.history.back();
        }));

        return tg.BackButton;
    } catch (error) {
        console.error('Failed to setup back button:', error);
        return null;
    }
}

/**
 * Send data back to Telegram bot
 * @param {Object} data - Data to send
 */
export function sendDataToTelegram(data) {
    if (window.Telegram?.WebApp) {
        window.Telegram.WebApp.sendData(JSON.stringify(data));
        window.Telegram.WebApp.close();
    }
}

/**
 * Get Telegram user data
 * @returns {Object|null} User data or null
 */
export function getTelegramUser() {
    if (window.Telegram?.WebApp?.initDataUnsafe?.user) {
        return window.Telegram.WebApp.initDataUnsafe.user;
    }
    return null;
}
