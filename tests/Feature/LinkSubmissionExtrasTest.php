<?php

namespace Tests\Feature;

use App\DTO\Links\LinkData;
use App\Events\NewlinkNotifyEvent;
use App\Models\Admin;
use App\Models\LinkCustomField;
use App\Models\Links;
use App\Services\Admin\LinkService;
use App\Services\Links\CustomFieldService;
use App\Services\Links\LinkScreenshotService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LinkSubmissionExtrasTest extends TestCase
{
    private ?string $temporaryStorage = null;

    /** Configure isolation before providers can resolve a database connection. */
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->afterBootstrapping(LoadConfiguration::class, function ($app): void {
            $app['config']->set([
                'database.default' => 'submission_test',
                'database.connections' => [
                    'submission_test' => [
                        'driver' => 'sqlite',
                        'database' => ':memory:',
                        'prefix' => '',
                        'foreign_key_constraints' => true,
                    ],
                ],
                'captcha.disable' => true,
                'mail.default' => 'array',
                'session.driver' => 'array',
                'cache.default' => 'array',
            ]);
        });
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Never run schema mutations on a connection inherited from the local .env.
        $this->assertSame('submission_test', DB::getDefaultConnection());
        $this->assertSame(':memory:', DB::connection()->getDatabaseName());
        $this->assertSame('sqlite', DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME));

        $this->temporaryStorage = sys_get_temp_dir().'/catalog-submission-test-'.bin2hex(random_bytes(8));
        $this->app->useStoragePath($this->temporaryStorage);
        File::ensureDirectoryExists($this->temporaryStorage.'/framework/views');
        config(['view.compiled' => $this->temporaryStorage.'/framework/views']);
        Storage::fake('links');
        Event::fake([NewlinkNotifyEvent::class]);

        $schema = DB::connection()->getSchemaBuilder();
        $schema->create('catalog', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
        $schema->create('settings', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
            $table->text('value');
            $table->text('description')->nullable();
        });
        $schema->create('admin', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('login')->unique();
            $table->string('password');
            $table->string('name')->nullable();
            $table->timestamps();
        });
        // The production links migration creates a MySQL fulltext index. These
        // submission tests need its columns, not the unrelated search index.
        $schema->create('links', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
            $table->string('url')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('email')->nullable();
            $table->text('description');
            $table->text('keywords')->nullable();
            $table->text('full_description');
            $table->unsignedInteger('catalog_id')->nullable();
            $table->string('status', 10);
            $table->integer('views')->default(0);
            $table->text('comment')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
        (require database_path('migrations/2026_10_23_120000_create_link_custom_fields_table.php'))->up();
    }

    protected function tearDown(): void
    {
        DB::purge('submission_test');
        if ($this->temporaryStorage !== null) {
            File::deleteDirectory($this->temporaryStorage);
        }
        parent::tearDown();
    }

    #[DataProvider('invalidScreenshots')]
    public function test_public_submission_rejects_invalid_screenshots_without_storing_a_link(string $kind): void
    {
        $file = match ($kind) {
            'disguised-script' => UploadedFile::fake()->createWithContent('preview.png', '<?php echo "not an image";'),
            'svg' => UploadedFile::fake()->createWithContent('preview.svg', '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"><rect width="10" height="10"/></svg>'),
            'gif' => UploadedFile::fake()->image('preview.gif', 80, 60),
            'too-large' => UploadedFile::fake()->image('preview.png', 80, 60)->size(5121),
            'too-wide' => UploadedFile::fake()->image('preview.png', 6001, 1),
            'too-tall' => UploadedFile::fake()->image('preview.png', 1, 6001),
        };

        $this->post(route('add'), [...$this->payload(), 'screenshot' => $file])
            ->assertSessionHasErrors('screenshot');

        $this->assertSame(0, Links::count());
        $this->assertSame([], Storage::disk('links')->allFiles());
        Event::assertNotDispatched(NewlinkNotifyEvent::class);
    }

    public static function invalidScreenshots(): array
    {
        return [
            'script disguised as PNG' => ['disguised-script'],
            'SVG is not an accepted screenshot' => ['svg'],
            'GIF is not an accepted screenshot' => ['gif'],
            'over five MiB' => ['too-large'],
            'over width limit' => ['too-wide'],
            'over height limit' => ['too-tall'],
        ];
    }

    public function test_valid_submission_stores_a_resized_screenshot_and_configured_values(): void
    {
        $text = $this->field('text', ['is_required' => true]);
        $select = $this->field('select', ['options' => ['Личный проект', 'Команда']]);
        $checkbox = $this->field('checkbox');

        $this->post(route('add'), [
            ...$this->payload(),
            'screenshot' => UploadedFile::fake()->image('untrusted-original-name.png', 2200, 1400),
            'custom_fields' => [$text->id => 'Небольшая команда', $select->id => 'Команда', $checkbox->id => '1'],
        ])->assertRedirect(route('addurl'))->assertSessionHasNoErrors();

        $link = Links::sole();
        $this->assertSame('new', $link->status);
        $this->assertSame('Небольшая команда', $link->custom_fields[$text->id]);
        $this->assertSame('Команда', $link->custom_fields[$select->id]);
        $this->assertTrue($link->custom_fields[$checkbox->id]);
        $this->assertMatchesRegularExpression('/^upload-[a-f0-9-]{36}\.jpg$/', $link->image);
        Storage::disk('links')->assertExists('url/'.$link->image);
        [$width, $height, $type] = getimagesize(Storage::disk('links')->path('url/'.$link->image));
        $this->assertLessThanOrEqual(1600, $width);
        $this->assertLessThanOrEqual(1200, $height);
        $this->assertSame(IMAGETYPE_JPEG, $type);
        Event::assertDispatched(NewlinkNotifyEvent::class);
    }

    #[DataProvider('invalidCustomValues')]
    public function test_public_submission_enforces_configured_field_constraints(
        string $type,
        mixed $value,
        array $attributes,
        bool $omit = false,
    ): void {
        $field = $this->field($type, $attributes);
        $payload = [
            ...$this->payload(),
            'screenshot' => UploadedFile::fake()->image('preview.jpg', 80, 60),
        ];
        if (! $omit) {
            $payload['custom_fields'] = [$field->id => $value];
        }

        $this->post(route('add'), $payload)->assertSessionHasErrors('custom_fields.'.$field->id);
        $this->assertSame(0, Links::count());
        $this->assertSame([], Storage::disk('links')->allFiles());
    }

    public static function invalidCustomValues(): array
    {
        return [
            'missing required text' => ['text', null, ['is_required' => true], true],
            'text cannot be an array' => ['text', ['nested'], []],
            'text has a length limit' => ['text', str_repeat('я', 256), []],
            'textarea has a length limit' => ['textarea', str_repeat('я', 2001), []],
            'number must be numeric' => ['number', 'несколько', []],
            'number has a magnitude limit' => ['number', '1000000000001', []],
            'URL rejects script scheme' => ['url', 'javascript:alert(1)', []],
            'URL rejects FTP scheme' => ['url', 'ftp://files.example/archive', []],
            'select rejects unconfigured option' => ['select', 'Неизвестный вариант', ['options' => ['Первый', 'Второй']]],
            'required checkbox must be accepted' => ['checkbox', '0', ['is_required' => true]],
            'optional checkbox is boolean' => ['checkbox', 'anything', []],
        ];
    }

    public function test_public_submission_rejects_unknown_and_disabled_field_keys(): void
    {
        $disabled = $this->field('text', ['is_active' => false]);

        foreach ([$disabled->id, 999999] as $key) {
            $this->post(route('add'), [
                ...$this->payload(),
                'screenshot' => UploadedFile::fake()->image('preview.jpg', 80, 60),
                'custom_fields' => [$key => 'Незапрошенное значение'],
            ])->assertSessionHasErrors('custom_fields');
        }

        $this->assertSame(0, Links::count());
        $this->assertSame([], Storage::disk('links')->allFiles());
    }

    public function test_optional_values_and_checkbox_defaults_are_normalized(): void
    {
        $text = $this->field('text');
        $checkbox = $this->field('checkbox');
        $service = $this->app->make(CustomFieldService::class);

        $this->assertTrue(Validator::make([], $service->rules())->passes());
        $values = $service->normalize([]);
        $this->assertNull($values[$text->id]);
        $this->assertFalse($values[$checkbox->id]);
    }

    public function test_admin_can_edit_disabled_definitions_without_requiring_them(): void
    {
        $disabled = $this->field('text', ['is_active' => false, 'is_required' => true]);
        $service = $this->app->make(CustomFieldService::class);
        $values = ['custom_fields' => [$disabled->id => 'Сохранённое служебное значение']];

        $this->assertTrue(Validator::make($values, $service->rules(true))->passes());
        $this->assertTrue(Validator::make([], $service->rules(true))->passes());
        $normalized = $service->normalize($values['custom_fields'], [], true);
        $this->assertSame('Сохранённое служебное значение', $normalized[$disabled->id]);
    }

    public function test_admin_http_edit_preserves_an_existing_screenshot(): void
    {
        $field = $this->field('text');
        $filename = $this->app->make(LinkScreenshotService::class)
            ->store(UploadedFile::fake()->image('original.png', 120, 80));
        $link = $this->existingLink(['image' => $filename, 'custom_fields' => [$field->id => 'До редактирования']]);
        $admin = Admin::create(['login' => 'isolated-test-admin', 'password' => 'unused-in-acting-as']);

        $this->actingAs($admin, 'admin')->put(route('cp.links.update'), [
            ...$this->payload(),
            'id' => $link->id,
            'name' => 'Обновлённое название',
            'url' => $link->url,
            'custom_fields' => [$field->id => 'После редактирования'],
        ])->assertRedirect(route('cp.links.index'))->assertSessionHasNoErrors();

        $link->refresh();
        $this->assertSame('Обновлённое название', $link->name);
        $this->assertSame($filename, $link->image);
        $this->assertSame('После редактирования', $link->custom_fields[$field->id]);
        Storage::disk('links')->assertExists('url/'.$filename);
    }

    public function test_public_card_escapes_answers_and_hides_private_or_inactive_fields(): void
    {
        $public = $this->field('text', ['label' => 'Публичное описание']);
        $private = $this->field('text', ['label' => 'Скрытое описание', 'is_public' => false]);
        $inactive = $this->field('text', ['label' => 'Отключённое описание', 'is_active' => false]);
        $checkbox = $this->field('checkbox', ['label' => 'Ответ на флажок']);
        $number = $this->field('number', ['label' => 'Нулевое число']);
        $textZero = $this->field('text', ['label' => 'Нулевая строка']);
        $unsafeText = '<script>window.catalogExtraFieldXss = true;</script>';
        $link = $this->existingLink(['custom_fields' => [
            $public->id => $unsafeText,
            $private->id => 'PRIVATE-ANSWER-DO-NOT-EXPOSE',
            $inactive->id => 'INACTIVE-ANSWER-DO-NOT-EXPOSE',
            $checkbox->id => false,
            $number->id => 0,
            $textZero->id => '0',
        ]]);

        $response = $this->withHeader('Accept-Language', 'ru')->get(route('info', ['id' => $link->id]));
        $response->assertOk()
            ->assertSee($unsafeText)
            ->assertDontSee($unsafeText, false)
            ->assertDontSee('PRIVATE-ANSWER-DO-NOT-EXPOSE')
            ->assertDontSee('INACTIVE-ANSWER-DO-NOT-EXPOSE')
            ->assertDontSee('Скрытое описание')
            ->assertDontSee('Отключённое описание');

        // Read displayed values from their semantic definition-list rows so a
        // zero elsewhere on the page cannot accidentally satisfy the assertion.
        $document = new \DOMDocument;
        @$document->loadHTML('<?xml encoding="UTF-8">'.$response->getContent());
        $xpath = new \DOMXPath($document);
        $displayed = [];
        foreach ($xpath->query('//section[@aria-labelledby="site-custom-details-heading"]//dt') as $label) {
            $value = $xpath->query('following-sibling::dd[1]', $label)->item(0);
            $displayed[trim($label->textContent)] = trim($value->textContent);
        }

        $this->assertSame($unsafeText, $displayed['Публичное описание']);
        $this->assertSame('Нет', $displayed['Ответ на флажок']);
        $this->assertSame('0', $displayed['Нулевое число']);
        $this->assertSame('0', $displayed['Нулевая строка']);
        $this->assertCount(4, $displayed);
        $this->assertFalse($link->fresh()->custom_fields[$checkbox->id]);
        $this->assertSame(0, $link->fresh()->custom_fields[$number->id]);
    }

    public function test_admin_http_edit_without_custom_fields_preserves_optional_answers(): void
    {
        $text = $this->field('text');
        $checkbox = $this->field('checkbox');
        $values = [$text->id => 'Ответ до редактирования', $checkbox->id => true];
        $link = $this->existingLink(['image' => 'legacy.jpg', 'custom_fields' => $values]);
        Storage::disk('links')->put('url/legacy.jpg', 'existing legacy image');
        $admin = Admin::create(['login' => 'partial-edit-admin', 'password' => 'unused-in-acting-as']);

        $this->actingAs($admin, 'admin')->put(route('cp.links.update'), [
            ...$this->payload(),
            'id' => $link->id,
            'name' => 'Название после частичного редактирования',
            'url' => $link->url,
        ])->assertRedirect(route('cp.links.index'))->assertSessionHasNoErrors();

        $link->refresh();
        $this->assertSame('Название после частичного редактирования', $link->name);
        $this->assertSame($values, $link->custom_fields);
        $this->assertSame('legacy.jpg', $link->image);
        Storage::disk('links')->assertExists('url/legacy.jpg');
    }

    public function test_service_update_preserves_custom_values_when_not_provided(): void
    {
        $field = $this->field('text');
        $link = $this->existingLink(['image' => 'legacy.jpg', 'custom_fields' => [$field->id => 'Существующий ответ']]);
        Storage::disk('links')->put('url/legacy.jpg', 'existing legacy image');

        $this->app->make(LinkService::class)->update($this->updateData($link));

        $link->refresh();
        $this->assertSame('legacy.jpg', $link->image);
        $this->assertSame('Существующий ответ', $link->custom_fields[$field->id]);
        Storage::disk('links')->assertExists('url/legacy.jpg');
    }

    public function test_replacing_a_screenshot_deletes_the_previous_managed_upload(): void
    {
        $screenshots = $this->app->make(LinkScreenshotService::class);
        $previous = $screenshots->store(UploadedFile::fake()->image('old.png', 120, 80));
        $link = $this->existingLink(['image' => $previous]);

        $this->app->make(LinkService::class)->update(
            $this->updateData($link),
            UploadedFile::fake()->image('new.png', 160, 120),
        );

        $link->refresh();
        $this->assertNotSame($previous, $link->image);
        Storage::disk('links')->assertMissing('url/'.$previous);
        Storage::disk('links')->assertExists('url/'.$link->image);
        $this->assertCount(1, Storage::disk('links')->allFiles());
    }

    public function test_explicit_removal_clears_the_link_and_deletes_its_managed_upload(): void
    {
        $filename = $this->app->make(LinkScreenshotService::class)
            ->store(UploadedFile::fake()->image('old.png', 120, 80));
        $link = $this->existingLink(['image' => $filename]);

        $this->app->make(LinkService::class)->update($this->updateData($link), null, true);

        $this->assertEmpty($link->fresh()->image);
        Storage::disk('links')->assertMissing('url/'.$filename);
    }

    public function test_removal_does_not_delete_shared_demo_or_legacy_files(): void
    {
        foreach (['demo-technology.svg', 'shared-preview.jpg'] as $filename) {
            Storage::disk('links')->put('url/'.$filename, 'shared image');
            $link = $this->existingLink(['image' => $filename, 'url' => 'https://'.str_replace('.', '-', $filename).'.example']);

            $this->app->make(LinkService::class)->update($this->updateData($link), null, true);

            $this->assertEmpty($link->fresh()->image);
            Storage::disk('links')->assertExists('url/'.$filename);
        }
    }

    private function field(string $type, array $attributes = []): LinkCustomField
    {
        return LinkCustomField::create([
            'label' => 'Тестовое поле '.$type,
            'type' => $type,
            'is_active' => true,
            'is_required' => false,
            'is_public' => true,
            'sort_order' => 0,
            ...$attributes,
        ]);
    }

    private function payload(): array
    {
        return [
            'name' => 'Учебный каталог',
            'url' => 'https://submission-test.example',
            'description' => str_repeat('Описание учебного сайта для проверки формы. ', 4),
            'full_description' => str_repeat('Подробное описание вымышленного проекта и его тематических разделов. ', 5),
            'catalog_id' => '0',
            'captcha' => 'testing-only',
            'agree' => '1',
        ];
    }

    private function existingLink(array $attributes = []): Links
    {
        $payload = $this->payload();
        unset($payload['captcha'], $payload['agree']);

        return Links::create([
            ...$payload,
            'catalog_id' => null,
            'status' => 1,
            'image' => '',
            ...$attributes,
        ]);
    }

    private function updateData(Links $link): LinkData
    {
        return LinkData::fromArray([
            ...$this->payload(),
            'id' => $link->id,
            'url' => $link->url,
        ]);
    }
}
