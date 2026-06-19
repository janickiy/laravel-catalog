<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Install
{
    /**
     * Перенаправляет неустановленное приложение в мастер установки и закрывает установщик после установки.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isInstalled() && ! $request->is('install*')) {
            return redirect()->route('install.start');
        }

        if ($this->isInstalled() && $request->is('install*')) {
            if ($request->is('install/complete') && $request->session()->pull('install.completed', false)) {
                return $next($request);
            }

            throw new NotFoundHttpException;
        }

        return $next($request);
    }

    /**
     * Проверяет флаг установленности приложения.
     */
    private function isInstalled(): bool
    {
        return filter_var(config('app.installed'), FILTER_VALIDATE_BOOL);
    }
}
