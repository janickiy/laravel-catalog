<?php

namespace App\Http\Controllers;

use App\Http\Requests\Frontend\InstallAdminRequest;
use App\Http\Requests\Frontend\InstallRequest;
use App\Models\Admin;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class InstallController extends Controller
{
    private const APPLICATION_VERSION = '4.0.1';

    /**
     * Show the first installer step.
     */
    public function index(): View
    {
        return view('install.start');
    }

    /**
     * Show PHP and extension requirements.
     */
    public function requirements(): View
    {
        $requirements = $this->getRequirements();
        $allLoaded = $this->allRequirementsLoaded();

        return view('install.requirements', compact('requirements', 'allLoaded'));
    }

    /**
     * Show writable path checks after requirements pass.
     */
    public function permissions(): View|RedirectResponse
    {
        if (! $this->allRequirementsLoaded()) {
            return redirect()->route('install.requirements');
        }

        $folders = $this->getPermissions();
        $allGranted = $this->allPermissionsGranted();

        return view('install.permissions', compact('folders', 'allGranted'));
    }

    /**
     * Show the database connection form.
     */
    public function database(): View|RedirectResponse
    {
        if (! $this->allRequirementsLoaded()) {
            return redirect()->route('install.requirements');
        }

        if (! $this->allPermissionsGranted()) {
            return redirect()->route('install.permissions');
        }

        return view('install.database');
    }

    /**
     * Validate the database connection and store credentials in the session.
     */
    public function installation(InstallRequest $request): RedirectResponse
    {
        if (! $this->allRequirementsLoaded()) {
            return redirect()->route('install.requirements');
        }

        if (! $this->allPermissionsGranted()) {
            return redirect()->route('install.permissions');
        }

        $dbCredentials = [
            'host' => (string) $request->input('db_host'),
            'port' => (int) $request->input('db_port'),
            'database' => (string) $request->input('db_database'),
            'username' => (string) $request->input('db_username'),
            'password' => (string) $request->input('db_password', ''),
        ];

        if (! $this->dbCredentialsAreValid($dbCredentials)) {
            return redirect()
                ->route('install.database')
                ->withInput()
                ->withErrors(__('install.str.connection_to_database_cannot_be_established'));
        }

        Session::put('install.db_credentials', $dbCredentials);

        return redirect()->route('install.admin');
    }

    /**
     * Show the first administrator creation step.
     */
    public function admin(): View|RedirectResponse
    {
        if (! Session::has('install.db_credentials')) {
            return redirect()->route('install.database');
        }

        return view('install.installation');
    }

    /**
     * Write configuration, run migrations, and create the first administrator.
     */
    public function install(InstallAdminRequest $request): RedirectResponse
    {
        $previousEnv = file_exists(base_path('.env')) ? file_get_contents(base_path('.env')) : null;

        try {
            $db = Session::pull('install.db_credentials');

            if (! is_array($db)) {
                return redirect()->route('install.database');
            }

            $installLocale = $this->getInstallLocale();
            $env = $this->environmentTemplate();
            $env = $this->setEnvValue($env, 'APP_URL', url('/'));
            $env = $this->setEnvValue($env, 'APP_LOCALE', $installLocale);
            $env = $this->setEnvValue($env, 'APP_FALLBACK_LOCALE', Config::get('app.fallback_locale', 'en'));
            $env = $this->setEnvValue($env, 'VERSION', self::APPLICATION_VERSION);
            $env = $this->setEnvValue($env, 'DB_CONNECTION', 'mysql');
            $env = $this->setEnvValue($env, 'DB_HOST', $db['host']);
            $env = $this->setEnvValue($env, 'DB_PORT', (string) $db['port']);
            $env = $this->setEnvValue($env, 'DB_DATABASE', $db['database']);
            $env = $this->setEnvValue($env, 'DB_USERNAME', $db['username']);
            $env = $this->setEnvValue($env, 'DB_PASSWORD', $db['password'] ?? '');

            file_put_contents(base_path('.env'), $env);
            $this->reloadEnv();
            $this->setDatabaseCredentials($db);

            config([
                'app.installed' => true,
                'app.locale' => $installLocale,
                'app.installed_locale' => $installLocale,
            ]);
            app()->setLocale($installLocale);

            Artisan::call('config:clear');
            Artisan::call('key:generate', ['--force' => true]);
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\CatalogSeeder',
                '--force' => true,
            ]);

            Admin::updateOrCreate(
                ['login' => (string) $request->input('login')],
                [
                    'name' => 'Admin',
                    'password' => Hash::make((string) $request->input('password')),
                ],
            );

            return redirect()
                ->route('index')
                ->withCookie(Cookie::forever('lang', $installLocale));
        } catch (\Throwable $exception) {
            $this->restoreEnvironment($previousEnv);
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());

            return redirect()->route('install.error');
        }
    }

    /**
     * Show the successful installation page.
     */
    public function complete(): View
    {
        $locale = $this->getConfiguredLocale();

        app()->setLocale($locale);
        Cookie::queue(Cookie::forever('lang', $locale));

        return view('install.complete');
    }

    /**
     * Show the installation error page.
     */
    public function error(): View
    {
        return view('install.error');
    }

    /**
     * Handle installer AJAX actions, including language changes.
     */
    public function ajax(Request $request): JsonResponse
    {
        if ($request->input('action') === 'change_lng') {
            $locale = (string) $request->input('locale');

            if ($locale !== '' && in_array($locale, Config::get('app.locales', []), true)) {
                Session::put('install.locale', $locale);
                app()->setLocale($locale);
                Cookie::queue(Cookie::forever('lang', $locale));
            }

            return response()->json(['result' => true]);
        }

        return response()->json(['result' => false]);
    }

    /**
     * Return the PHP and extension requirements for the project.
     */
    private function getRequirements(): array
    {
        return [
            'PHP Version (>= 8.3.0)' => version_compare(PHP_VERSION, '8.3.0', '>='),
            'Ctype Extension' => extension_loaded('ctype'),
            'cURL Extension' => extension_loaded('curl'),
            'Fileinfo Extension' => extension_loaded('fileinfo'),
            'JSON PHP Extension' => extension_loaded('json'),
            'Mbstring Extension' => extension_loaded('mbstring'),
            'OpenSSL Extension' => extension_loaded('openssl'),
            'PDO Extension' => extension_loaded('PDO'),
            'PDO MySQL Extension' => extension_loaded('pdo_mysql'),
            'Tokenizer Extension' => extension_loaded('tokenizer'),
            'XML Extension' => extension_loaded('xml'),
            'Zip Extension' => extension_loaded('zip'),
        ];
    }

    /**
     * Determine whether all system requirements are loaded.
     */
    private function allRequirementsLoaded(): bool
    {
        foreach ($this->getRequirements() as $loaded) {
            if ($loaded === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Return the directories that require write permissions.
     */
    private function getPermissions(): array
    {
        return [
            'storage/app' => is_writable(storage_path('app')),
            'storage/framework/cache' => is_writable(storage_path('framework/cache')),
            'storage/framework/sessions' => is_writable(storage_path('framework/sessions')),
            'storage/framework/views' => is_writable(storage_path('framework/views')),
            'storage/logs' => is_writable(storage_path('logs')),
            'bootstrap/cache' => is_writable(base_path('bootstrap/cache')),
            'Base Directory' => is_writable(base_path()),
        ];
    }

    /**
     * Determine whether all required directories are writable.
     */
    private function allPermissionsGranted(): bool
    {
        foreach ($this->getPermissions() as $granted) {
            if ($granted === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate the database connection with the submitted credentials.
     */
    private function dbCredentialsAreValid(array $credentials): bool
    {
        $this->setDatabaseCredentials($credentials);

        try {
            DB::connection()->getPdo();
        } catch (\Throwable $exception) {
            Log::info($exception->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Apply database credentials to the runtime configuration.
     */
    private function setDatabaseCredentials(array $credentials): void
    {
        $default = config('database.default');

        config([
            "database.connections.{$default}.host" => $credentials['host'],
            "database.connections.{$default}.port" => (string) $credentials['port'],
            "database.connections.{$default}.database" => $credentials['database'],
            "database.connections.{$default}.username" => $credentials['username'],
            "database.connections.{$default}.password" => $credentials['password'] ?? '',
        ]);

        DB::purge($default);
        DB::reconnect($default);
    }

    /**
     * Return the supported locale selected during installation.
     */
    private function getInstallLocale(): string
    {
        $locale = Session::get('install.locale', app()->getLocale());

        return in_array($locale, Config::get('app.locales', []), true)
            ? $locale
            : Config::get('app.fallback_locale', 'en');
    }

    /**
     * Return the installed application locale from configuration.
     */
    private function getConfiguredLocale(): string
    {
        $locale = Config::get('app.installed_locale', Config::get('app.fallback_locale', 'en'));

        return in_array($locale, Config::get('app.locales', []), true)
            ? $locale
            : Config::get('app.fallback_locale', 'en');
    }

    /**
     * Load the environment template from .env.example or the current .env.
     */
    private function environmentTemplate(): string
    {
        if (file_exists(base_path('.env.example'))) {
            return (string) file_get_contents(base_path('.env.example'));
        }

        return file_exists(base_path('.env'))
            ? (string) file_get_contents(base_path('.env'))
            : '';
    }

    /**
     * Set or append one value in .env contents.
     */
    private function setEnvValue(string $contents, string $key, string $value): string
    {
        $line = $key.'='.$this->formatEnvValue($value);

        if (preg_match('/^'.preg_quote($key, '/').'=.*/m', $contents)) {
            $updatedContents = preg_replace('/^'.preg_quote($key, '/').'=.*/m', $line, $contents);

            return $updatedContents ?? $contents;
        }

        return rtrim($contents).PHP_EOL.$line.PHP_EOL;
    }

    /**
     * Format a value for safe .env writing.
     */
    private function formatEnvValue(string $value): string
    {
        if ($value === '') {
            return '';
        }

        if (preg_match('/[\s#"\'=]/', $value) === 1) {
            return '"'.str_replace('"', '\"', $value).'"';
        }

        return $value;
    }

    /**
     * Reload environment variables after writing .env.
     */
    private function reloadEnv(): void
    {
        (new LoadEnvironmentVariables)->bootstrap(app());
    }

    /**
     * Restore the previous .env after a failed installation.
     */
    private function restoreEnvironment(?string $previousEnv): void
    {
        if ($previousEnv === null) {
            @unlink(base_path('.env'));

            return;
        }

        file_put_contents(base_path('.env'), $previousEnv);
        $this->reloadEnv();
    }
}
