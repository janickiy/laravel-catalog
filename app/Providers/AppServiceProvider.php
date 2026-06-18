<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Регистрирует сервисы приложения в контейнере.
     */
    public function register(): void
    {
        //
    }

    /**
     * Выполняет начальную настройку пагинации и Blade-директив.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Blade::directive('captcha', function (): string {
            return "<?php echo function_exists('captcha_img') ? captcha_img() : ''; ?>";
        });
    }
}
