<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateTelegramWebApp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // In development mode, allow bypass for testing
        if (config('app.debug') && $request->has('skip_auth')) {
            $request->attributes->set('telegram_user', [
                'id' => 123456789,
                'first_name' => 'Test',
                'last_name' => 'User',
                'username' => 'testuser'
            ]);
            return $next($request);
        }

        // Check if user is already authenticated in session
        if (session()->has('telegram_user')) {
            $request->attributes->set('telegram_user', session('telegram_user'));
            return $next($request);
        }

        // Get initData from Telegram Web App
        $initData = $request->header('X-Telegram-Init-Data')
                    ?? $request->query('tgWebAppData')
                    ?? $request->input('_auth');

        if (!$initData) {
            // For development, show helpful error
            if (config('app.debug')) {
                return response()->view('errors.telegram-auth', [
                    'error' => 'Missing Telegram initData',
                    'help' => 'Open this page from Telegram Bot or add ?skip_auth=1 for testing'
                ], 401);
            }

            return response()->json([
                'error' => 'Missing Telegram initData'
            ], 401);
        }

        // Parse initData
        parse_str($initData, $data);

        if (!isset($data['hash'])) {
            if (config('app.debug')) {
                return response()->view('errors.telegram-auth', [
                    'error' => 'Invalid initData format',
                    'data' => $data
                ], 401);
            }

            return response()->json([
                'error' => 'Invalid initData format'
            ], 401);
        }

        // Extract and remove hash
        $hash = $data['hash'];
        unset($data['hash']);

        // Build data check string
        $dataCheckArr = [];
        foreach ($data as $key => $value) {
            $dataCheckArr[] = $key . '=' . $value;
        }
        sort($dataCheckArr);
        $dataCheckString = implode("\n", $dataCheckArr);

        // Get bot token
        $botToken = config('telegram.bots.mybot.token');

        if (!$botToken) {
            return response()->json([
                'error' => 'Bot token not configured'
            ], 500);
        }

        // Create secret key
        $secretKey = hash_hmac('sha256', $botToken, 'WebAppData', true);

        // Calculate hash
        $calculatedHash = hash_hmac('sha256', $dataCheckString, $secretKey);

        // Verify hash
        if (!hash_equals($calculatedHash, $hash)) {
            if (config('app.debug')) {
                return response()->view('errors.telegram-auth', [
                    'error' => 'Invalid initData signature',
                    'expected' => $calculatedHash,
                    'received' => $hash
                ], 403);
            }

            return response()->json([
                'error' => 'Invalid initData signature'
            ], 403);
        }

        // Parse user data if available
        if (isset($data['user'])) {
            $userData = json_decode($data['user'], true);
            $request->attributes->set('telegram_user', $userData);

            // Store in session for subsequent requests
            session(['telegram_user' => $userData]);
        }

        return $next($request);
    }
}
