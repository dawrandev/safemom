<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for onboarding and profile store routes
        if ($request->routeIs('telegram.webapp.onboarding') ||
            $request->routeIs('telegram.webapp.profile.store')) {
            return $next($request);
        }

        // Get telegram user from request attributes
        $telegramUser = $request->attributes->get('telegram_user', []);

        if (empty($telegramUser['id'])) {
            return redirect()->route('telegram.webapp.onboarding');
        }

        // Get authenticated user
        $user = User::where('telegram_id', $telegramUser['id'])->first();

        // Redirect to onboarding if profile is incomplete
        if (!$user || !$user->profile || !$user->profile->lmp_date) {
            return redirect()->route('telegram.webapp.onboarding');
        }

        return $next($request);
    }
}
