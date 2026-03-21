<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Authentication Error' }}</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--tg-theme-bg-color, #ffffff);
            color: var(--tg-theme-text-color, #000000);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .error-container {
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .error-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        .error-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--tg-theme-text-color, #000000);
        }

        .error-message {
            font-size: 16px;
            line-height: 1.5;
            color: var(--tg-theme-hint-color, #999999);
            margin-bottom: 24px;
        }

        .close-button {
            background: var(--tg-theme-button-color, #3390ec);
            color: var(--tg-theme-button-text-color, #ffffff);
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .close-button:hover {
            opacity: 0.9;
        }

        .close-button:active {
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1 class="error-title">{{ $title ?? $error ?? 'Authentication Error' }}</h1>
        <p class="error-message">{{ $message ?? $help ?? 'Unable to verify your authentication. Please try again.' }}</p>

        @if(isset($data))
            <div style="margin: 20px 0; padding: 12px; background: var(--tg-theme-secondary-bg-color, #f0f0f0); border-radius: 8px; font-size: 12px; text-align: left;">
                <strong>Debug data:</strong>
                <pre style="margin: 8px 0; overflow-x: auto;">{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif

        @if(isset($expected) && isset($received))
            <div style="margin: 20px 0; padding: 12px; background: var(--tg-theme-secondary-bg-color, #f0f0f0); border-radius: 8px; font-size: 12px; text-align: left;">
                <strong>Hash mismatch:</strong><br>
                Expected: {{ substr($expected, 0, 20) }}...<br>
                Received: {{ substr($received, 0, 20) }}...
            </div>
        @endif

        <button class="close-button" onclick="closeApp()">Close</button>
    </div>

    <script>
        const tg = window.Telegram.WebApp;
        tg.ready();
        tg.expand();

        function closeApp() {
            tg.close();
        }
    </script>
</body>
</html>
