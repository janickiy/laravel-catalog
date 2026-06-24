<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registers application services in the container.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstraps pagination and Blade directives.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Blade::directive('captcha', function (): string {
            return "<?php echo function_exists('captcha_img') ? captcha_img() : ''; ?>";
        });
    }
}
