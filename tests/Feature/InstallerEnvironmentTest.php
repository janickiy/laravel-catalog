<?php

namespace Tests\Feature;

use App\Http\Controllers\InstallController;
use App\Http\Middleware\PrepareInstallation;
use App\Http\Requests\Frontend\InstallAdminRequest;
use App\Models\Admin;
use Dotenv\Dotenv;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class InstallerEnvironmentTest extends TestCase
{
    private string $originalBasePath;

    private string $projectPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalBasePath = $this->app->basePath();
        $this->projectPath = sys_get_temp_dir().'/mylinks-installer-'.bin2hex(random_bytes(8));
        mkdir($this->projectPath);
        $this->app->setBasePath($this->projectPath);

        file_put_contents($this->projectPath.'/.env.example', "APP_NAME=\"Installer Test\"\nAPP_KEY=\nSESSION_DRIVER=file\n");
        touch($this->projectPath.'/test.sqlite');

        config([
            'database.default' => 'installer_test',
            'database.connections.installer_test' => [
                'driver' => 'sqlite',
                'database' => $this->projectPath.'/test.sqlite',
                'prefix' => '',
            ],
        ]);

        DB::connection()->getSchemaBuilder()->create('admin', function (Blueprint $table): void {
            $table->id();
            $table->string('login')->unique();
            $table->string('password');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        DB::purge('installer_test');
        $this->app->setBasePath($this->originalBasePath);

        foreach (['.env', '.env.example', 'test.sqlite', 'test.sqlite-shm', 'test.sqlite-wal'] as $file) {
            if (file_exists($this->projectPath.'/'.$file)) {
                unlink($this->projectPath.'/'.$file);
            }
        }
        if (is_dir($this->projectPath.'/storage/app')) {
            unlink($this->projectPath.'/storage/app/install.key');
            rmdir($this->projectPath.'/storage/app');
            rmdir($this->projectPath.'/storage');
        }
        rmdir($this->projectPath);

        parent::tearDown();
    }

    public function test_final_step_preserves_the_installer_key_and_environment_template(): void
    {
        $installerKey = 'base64:'.base64_encode(random_bytes(32));
        config(['app.key' => $installerKey]);

        Session::put('install.db_credentials', [
            'host' => 'localhost',
            'port' => 3306,
            'database' => $this->projectPath.'/test.sqlite',
            'username' => 'test',
            'password' => '',
        ]);

        Artisan::shouldReceive('call')->once()->with('config:clear')->andReturn(0);
        Artisan::shouldReceive('call')->once()->with('migrate', ['--force' => true])->andReturn(0);
        Artisan::shouldReceive('call')->once()->with('db:seed', [
            '--class' => 'Database\\Seeders\\CatalogSeeder', '--force' => true,
        ])->andReturn(0);
        Artisan::shouldReceive('call')->once()->with('db:seed', [
            '--class' => 'Database\\Seeders\\SettingsSeeder', '--force' => true,
        ])->andReturn(0);

        $request = InstallAdminRequest::create('https://site1.local/install/install-app', 'POST', [
            'login' => 'test-admin',
            'password' => 'installer-test-password',
        ]);
        $this->app->instance('request', $request);

        $response = $this->app->make(InstallController::class)->install($request);

        $this->assertSame(route('index'), $response->getTargetUrl());
        $this->assertFileExists($this->projectPath.'/.env');
        $environment = Dotenv::parse(file_get_contents($this->projectPath.'/.env'));
        $this->assertSame($installerKey, $environment['APP_KEY']);
        $this->assertSame('Installer Test', $environment['APP_NAME']);
        $this->assertSame('file', $environment['SESSION_DRIVER']);
        $this->assertTrue(Admin::where('login', 'test-admin')->exists());
    }

    public function test_initial_redirect_prepares_a_persistent_key_for_cookie_middleware(): void
    {
        mkdir($this->projectPath.'/storage/app', 0755, true);
        config(['app.key' => null]);
        $this->app->forgetInstance('encrypter');

        $response = (new PrepareInstallation)->handle(
            Request::create('https://site1.local/'),
            fn () => $this->fail('A fresh installation must redirect to the installer.')
        );

        $this->assertSame(route('install.start'), $response->getTargetUrl());
        $this->assertSame(config('app.key'), trim(file_get_contents($this->projectPath.'/storage/app/install.key')));
        $encrypter = $this->app->make('encrypter');
        $this->assertSame('installer-session', $encrypter->decryptString($encrypter->encryptString('installer-session')));
    }
}
