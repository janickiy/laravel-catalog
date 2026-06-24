<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrepareInstallation
{
    /**
     * Prepares a fresh project for the installer before cookie and session middleware run.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->environmentExists() && $request->is('install*')) {
            return redirect()->route('index');
        }

        if ($this->environmentExists()) {
            return $next($request);
        }

        if ($request->is('install*')) {
            $this->ensureApplicationKey();

            return $next($request);
        }

        return redirect()->route('install.start');
    }

    /**
     * Prepares a stable application key for cookies and CSRF during installation.
     */
    private function ensureApplicationKey(): void
    {
        if (filled(config('app.key'))) {
            return;
        }

        $keyPath = storage_path('app/install.key');
        $key = file_exists($keyPath)
            ? trim((string) file_get_contents($keyPath))
            : 'base64:'.base64_encode(random_bytes(32));

        config(['app.key' => $key]);

        if (! file_exists($keyPath)) {
            @file_put_contents($keyPath, $key);
        }
    }

    /**
     * Checks whether the environment file exists in the project root.
     */
    private function environmentExists(): bool
    {
        return file_exists(base_path('.env'));
    }
}
