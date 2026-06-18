<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpsMiddleware
{
    /**
     * Перенаправляет HTTP-запросы на HTTPS.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->secure()) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
