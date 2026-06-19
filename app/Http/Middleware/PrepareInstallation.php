<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrepareInstallation
{
    /**
     * Готовит свежий проект к запуску установщика до работы cookie и session middleware.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isInstalled()) {
            return $next($request);
        }

        if ($request->is('install*')) {
            $this->ensureApplicationKey();

            return $next($request);
        }

        return redirect()->route('install.start');
    }

    /**
     * Проверяет флаг установленности приложения.
     */
    private function isInstalled(): bool
    {
        return filter_var(config('app.installed'), FILTER_VALIDATE_BOOL);
    }

    /**
     * Готовит стабильный ключ приложения для работы cookies и CSRF во время установки.
     */
    private function ensureApplicationKey(): void
    {
        if (filled(config('app.key'))) {
            return;
        }

        $key = 'base64:'.base64_encode(random_bytes(32));

        config(['app.key' => $key]);

        $envPath = base_path('.env');
        $contents = file_exists($envPath)
            ? (string) file_get_contents($envPath)
            : $this->environmentTemplate();

        $contents = $this->setEnvValue($contents, 'APP_INSTALLED', 'false');
        $contents = $this->setEnvValue($contents, 'APP_KEY', $key);

        @file_put_contents($envPath, $contents);
    }

    /**
     * Загружает основу .env для первого запуска установщика.
     */
    private function environmentTemplate(): string
    {
        return file_exists(base_path('.env.example'))
            ? (string) file_get_contents(base_path('.env.example'))
            : '';
    }

    /**
     * Устанавливает или добавляет строку окружения.
     */
    private function setEnvValue(string $contents, string $key, string $value): string
    {
        $line = $key.'='.$value;

        if (preg_match('/^'.preg_quote($key, '/').'=.*/m', $contents)) {
            $updatedContents = preg_replace('/^'.preg_quote($key, '/').'=.*/m', $line, $contents);

            return $updatedContents ?? $contents;
        }

        return rtrim($contents).PHP_EOL.$line.PHP_EOL;
    }
}
