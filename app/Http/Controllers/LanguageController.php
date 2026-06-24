<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    /**
     * Store the selected interface language in a cookie and return the user back.
     */
    public function change(Request $request): JsonResponse|RedirectResponse
    {
        $locale = (string) $request->input('locale');

        abort_unless(in_array($locale, Config::get('app.locales', []), true), 422);

        App::setLocale($locale);
        Cookie::queue(Cookie::forever('lang', $locale));

        if ($request->expectsJson()) {
            return response()->json([
                'result' => true,
                'locale' => $locale,
            ]);
        }

        return back();
    }
}
