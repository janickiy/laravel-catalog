<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class Locale
{
    /**
     * Set the interface language from cookie, browser preferences, or application configuration.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rawLocale = $request->cookie('lang')
            ?: $this->detectLocaleFromBrowser((string) $request->header('Accept-Language'));

        if (in_array($rawLocale, config('app.locales', []), true)) {
            $locale = $rawLocale;
        } elseif (in_array(config('app.locale'), config('app.locales', []), true)) {
            $locale = config('app.locale');
        } else {
            $locale = config('app.fallback_locale', 'en');
        }

        App::setLocale($locale);

        return $next($request);
    }

    /**
     * Detect the best supported language from the Accept-Language header.
     */
    private function detectLocaleFromBrowser(string $acceptLanguage): ?string
    {
        if ($acceptLanguage === '') {
            return null;
        }

        $locales = config('app.locales', []);
        $acceptedLocales = [];

        foreach (explode(',', $acceptLanguage) as $part) {
            if (! preg_match('/^\s*([a-zA-Z_-]+)(?:;\s*q=([0-9.]+))?/', $part, $matches)) {
                continue;
            }

            $acceptedLocales[] = [
                'locale' => strtolower(str_replace('_', '-', $matches[1])),
                'quality' => isset($matches[2]) ? (float) $matches[2] : 1.0,
            ];
        }

        usort($acceptedLocales, fn (array $left, array $right): int => $right['quality'] <=> $left['quality']);

        foreach ($acceptedLocales as $acceptedLocale) {
            $matchedLocale = $this->matchSupportedLocale($acceptedLocale['locale'], $locales);

            if ($matchedLocale !== null) {
                return $matchedLocale;
            }
        }

        return null;
    }

    /**
     * Match a browser locale against the list of supported application locales.
     */
    private function matchSupportedLocale(string $locale, array $locales): ?string
    {
        if (in_array($locale, $locales, true)) {
            return $locale;
        }

        $language = substr($locale, 0, 2);

        foreach ($locales as $availableLocale) {
            if ($language === substr(strtolower($availableLocale), 0, 2)) {
                return $availableLocale;
            }
        }

        return null;
    }
}
