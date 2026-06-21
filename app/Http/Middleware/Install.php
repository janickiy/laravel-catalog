<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Install
{
    /**
     * Перенаправляет неустановленное приложение в мастер установки и закрывает установщик после установки.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->environmentExists() && ! $request->is('install*')) {
            return redirect()->route('install.start');
        }

        if ($this->environmentExists() && $request->is('install*')) {
            return redirect()->route('index');
        }

        return $next($request);
    }

    /**
     * Проверяет наличие файла окружения в корне проекта.
     */
    private function environmentExists(): bool
    {
        return file_exists(base_path('.env'));
    }
}
