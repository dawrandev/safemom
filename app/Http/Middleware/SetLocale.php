<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $availableLocales = ['uz', 'ru', 'en'];
        $defaultLocale = 'en';

        // Priority 1: Check for locale in URL parameter
        if ($request->has('lang') && in_array($request->get('lang'), $availableLocales)) {
            $locale = $request->get('lang');
            Session::put('locale', $locale);
            App::setLocale($locale);
            return $next($request);
        }

        // Priority 2: Check for locale in session
        if (Session::has('locale') && in_array(Session::get('locale'), $availableLocales)) {
            $locale = Session::get('locale');
            App::setLocale($locale);
            return $next($request);
        }

        // Priority 3: Check browser locale
        $browserLocale = $request->getPreferredLanguage($availableLocales);
        if ($browserLocale && in_array($browserLocale, $availableLocales)) {
            $locale = $browserLocale;
            Session::put('locale', $locale);
            App::setLocale($locale);
            return $next($request);
        }

        // Priority 4: Fallback to default locale
        Session::put('locale', $defaultLocale);
        App::setLocale($defaultLocale);

        return $next($request);
    }
}
