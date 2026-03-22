<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SafeMom')</title>

    <!-- Preconnect to external resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&family=Quicksand:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Iconify Icon Library -->
    <script src="https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>

    <!-- Telegram Web App SDK -->
    <script src="https://telegram.org/js/telegram-web-app.js"></script>

    <!-- Initialize Telegram Web App -->
    <script>
        (function() {
            // Initialize Telegram WebApp if available
            if (window.Telegram && window.Telegram.WebApp) {
                const tg = window.Telegram.WebApp;
                tg.ready();
                tg.expand();
                tg.setHeaderColor('#fdfbf7');
                tg.setBackgroundColor('#fdfbf7');
                console.log('Telegram WebApp initialized');
            } else {
                console.log('Running in browser (not Telegram WebApp)');
            }
        })();
    </script>

    <!-- Vite: Base CSS -->
    @vite(['resources/css/app.css', 'resources/css/telegram_bot/base.css', 'resources/css/telegram_bot/components.css'])

    <!-- Page-specific styles -->
    @stack('styles')
</head>

<body>
    @yield('content')

    <!-- Vite: Shared JavaScript -->
    @vite(['resources/js/telegram_bot/telegram-init.js', 'resources/js/telegram_bot/utils.js'])

    <!-- Page-specific scripts -->
    @stack('scripts')
</body>

</html>