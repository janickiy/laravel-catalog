<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Enforce the configured host after the installer has created .env and APP_URL.
     */
    protected function shouldSpecifyTrustedHosts(): bool
    {
        return file_exists($this->app->basePath('.env'))
            && parent::shouldSpecifyTrustedHosts();
    }

    /**
     * Returns the application's trusted host patterns.
     */
    public function hosts(): array
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
