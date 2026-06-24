<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Install
{
    /**
     * Redirects an uninstalled application to the installer and closes the installer after installation.
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
     * Checks whether the environment file exists in the project root.
     */
    private function environmentExists(): bool
    {
        return file_exists(base_path('.env'));
    }
}
