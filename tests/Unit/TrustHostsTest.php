<?php

namespace Tests\Unit;

use App\Http\Middleware\TrustHosts;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;

class TrustHostsTest extends TestCase
{
    private Application $app;

    private string $projectPath;

    private $previousContainer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->previousContainer = Container::getInstance();
        $this->projectPath = sys_get_temp_dir().'/mylinks-hosts-'.bin2hex(random_bytes(8));
        mkdir($this->projectPath);

        $this->app = new Application($this->projectPath);
        $this->app->instance('env', 'production');
        $this->app->instance('config', new Repository([
            'app' => ['url' => 'http://localhost'],
        ]));

        Request::setTrustedHosts([]);
    }

    protected function tearDown(): void
    {
        Request::setTrustedHosts([]);
        Container::setInstance($this->previousContainer);

        if (file_exists($this->projectPath.'/.env')) {
            unlink($this->projectPath.'/.env');
        }
        rmdir($this->projectPath);

        parent::tearDown();
    }

    public function test_fresh_installation_accepts_the_site_host_before_app_url_is_configured(): void
    {
        $request = Request::create('https://site1.local/MyLinksManager/');

        $host = (new TrustHosts($this->app))->handle($request, fn ($request) => $request->getHost());

        $this->assertSame('site1.local', $host);
    }

    public function test_installed_application_rejects_an_unconfigured_host(): void
    {
        touch($this->projectPath.'/.env');
        $this->app['config']->set('app.url', 'https://site1.local/MyLinksManager');
        $request = Request::create('https://untrusted.example/MyLinksManager/');

        $this->expectException(SuspiciousOperationException::class);

        (new TrustHosts($this->app))->handle($request, fn ($request) => $request->getHost());
    }

    public function test_installed_application_accepts_the_configured_host(): void
    {
        touch($this->projectPath.'/.env');
        $this->app['config']->set('app.url', 'https://site1.local/MyLinksManager');
        $request = Request::create('https://site1.local/MyLinksManager/');

        $host = (new TrustHosts($this->app))->handle($request, fn ($request) => $request->getHost());

        $this->assertSame('site1.local', $host);
    }
}
