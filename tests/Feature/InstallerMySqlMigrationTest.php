<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class InstallerMySqlMigrationTest extends TestCase
{
    private bool $databaseReady = false;

    protected function setUp(): void
    {
        parent::setUp();

        $database = getenv('CATALOG_MYSQL_TEST_DATABASE');
        if (! $database) {
            $this->markTestSkipped('Set CATALOG_MYSQL_TEST_* to use a disposable MySQL or MariaDB database.');
        }

        if (! str_starts_with($database, 'catalog_install_test')) {
            $this->fail('Migration tests require a database named catalog_install_test*.');
        }

        config([
            'database.default' => 'installer_mysql',
            'database.connections.installer_mysql' => array_replace(config('database.connections.mysql'), [
                'url' => null,
                'host' => getenv('CATALOG_MYSQL_TEST_HOST') ?: '127.0.0.1',
                'port' => getenv('CATALOG_MYSQL_TEST_PORT') ?: '3306',
                'database' => $database,
                'username' => getenv('CATALOG_MYSQL_TEST_USERNAME'),
                'password' => getenv('CATALOG_MYSQL_TEST_PASSWORD'),
                'unix_socket' => '',
            ]),
        ]);

        DB::purge('installer_mysql');
        DB::statement('SET SESSION default_storage_engine = MyISAM');
        $this->databaseReady = true;
        Schema::dropAllTables();
    }

    protected function tearDown(): void
    {
        if ($this->databaseReady) {
            Schema::dropAllTables();
            DB::purge('installer_mysql');
        }

        parent::tearDown();
    }

    public function test_fresh_installation_uses_innodb_even_when_the_server_defaults_to_myisam(): void
    {
        $this->assertSame('MyISAM', DB::selectOne('SELECT @@default_storage_engine AS engine')->engine);

        $this->assertSame(0, Artisan::call('migrate', ['--force' => true]));

        $tables = collect(Schema::getTables())->keyBy('name');
        foreach (['migrations', 'failed_jobs', 'personal_access_tokens', 'jobs', 'admin', 'feedback'] as $name) {
            $this->assertSame('InnoDB', $tables[$name]['engine'], $name);
        }
        $this->assertTokenIndexesExist();
    }

    public function test_retry_repairs_a_partial_myisam_table_and_preserves_its_rows(): void
    {
        // MySQL/MariaDB commit CREATE TABLE before Laravel adds the indexes.
        Schema::create('personal_access_tokens', function (Blueprint $table): void {
            $table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->string('tokenable_type');
            $table->unsignedBigInteger('tokenable_id');
            $table->string('name');
            $table->string('token', 64);
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
        DB::table('personal_access_tokens')->insert([
            'tokenable_type' => 'App\\Models\\Admin',
            'tokenable_id' => 1,
            'name' => 'preserved test token',
            'token' => str_repeat('a', 64),
        ]);

        $this->assertSame(0, Artisan::call('migrate', ['--force' => true]));
        $table = collect(Schema::getTables())->firstWhere('name', 'personal_access_tokens');
        $this->assertSame('InnoDB', $table['engine']);
        $this->assertSame('preserved test token', DB::table('personal_access_tokens')->value('name'));
        $this->assertTokenIndexesExist();

        // A complete table must also be safe to revisit after an interrupted run.
        (new \CreatePersonalAccessTokensTable)->up();
        $this->assertSame(1, DB::table('personal_access_tokens')->count());
        $this->assertCount(3, Schema::getIndexes('personal_access_tokens'));
    }

    private function assertTokenIndexesExist(): void
    {
        $this->assertTrue(Schema::hasIndex('personal_access_tokens', ['tokenable_type', 'tokenable_id']));
        $this->assertTrue(Schema::hasIndex('personal_access_tokens', ['token'], 'unique'));
    }
}
