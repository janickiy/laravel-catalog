<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\LinkCustomField;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LinkCustomFieldCrudTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // This suite never migrates, truncates or queries the configured application database.
        config([
            'database.default' => 'custom_fields_test',
            'database.connections.custom_fields_test' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
            'session.driver' => 'array',
        ]);
        DB::purge('custom_fields_test');
        $schema = DB::connection('custom_fields_test')->getSchemaBuilder();
        $schema->create('links', function (Blueprint $table): void {
            $table->id();
        });
        $schema->create('admin', function (Blueprint $table): void {
            $table->id();
            $table->string('login');
            $table->string('password');
            $table->timestamps();
        });
        $migration = require database_path('migrations/2026_10_23_120000_create_link_custom_fields_table.php');
        $migration->up();
    }

    protected function tearDown(): void
    {
        DB::purge('custom_fields_test');
        parent::tearDown();
    }

    private function administrator(): void
    {
        $admin = Admin::create(['login' => 'custom-field-test-admin', 'password' => 'unused']);
        $this->actingAs($admin, 'admin');
    }

    private function payload(array $overrides = []): array
    {
        return array_replace([
            'label' => 'Business hours',
            'type' => 'text',
            'help_text' => 'Describe when the service is available.',
            'sort_order' => 10,
            'is_active' => '1',
            'is_required' => '0',
            'is_public' => '0',
        ], $overrides);
    }

    public function test_management_requires_an_authenticated_administrator(): void
    {
        $this->get(route('cp.custom-fields.index'))->assertRedirect(route('login'));
        $this->post(route('cp.custom-fields.store'), $this->payload())->assertRedirect(route('login'));
        $this->assertSame(0, LinkCustomField::count());
    }

    public function test_administrator_can_create_edit_and_hide_a_select_field(): void
    {
        $this->administrator();
        $this->get(route('cp.custom-fields.create'))->assertOk()->assertSee('name="options_text"', false);
        $this->post(route('cp.custom-fields.store'), $this->payload([
            'type' => 'select',
            'options_text' => " Free \r\n\n Paid \n0",
        ]))->assertRedirect(route('cp.custom-fields.index'))->assertSessionHasNoErrors();

        $field = LinkCustomField::firstOrFail();
        $this->assertSame(['Free', 'Paid', '0'], $field->options);
        $this->assertTrue($field->is_active);
        $this->assertFalse($field->is_public);
        $this->get(route('cp.custom-fields.edit', $field))->assertOk()->assertSee('readonly', false);
        $this->put(route('cp.custom-fields.update', $field), $this->payload([
            'type' => 'select',
            'options_text' => "Free\nPaid\n0",
            'is_active' => '0',
            'is_required' => '1',
            'is_public' => '1',
        ]))->assertRedirect(route('cp.custom-fields.index'))->assertSessionHasNoErrors();
        $field->refresh();
        $this->assertFalse($field->is_active);
        $this->assertTrue($field->is_required);
        $this->assertTrue($field->is_public);
        $this->get(route('cp.custom-fields.index'))->assertOk()->assertSee('Business hours');
    }

    public function test_type_is_immutable_and_invalid_flags_and_order_are_rejected(): void
    {
        $this->administrator();
        $field = LinkCustomField::create($this->payload());
        $this->put(route('cp.custom-fields.update', $field), $this->payload([
            'type' => 'number',
            'is_public' => 'not-a-boolean',
            'sort_order' => 10000,
        ]))->assertSessionHasErrors(['type', 'is_public', 'sort_order']);
        $this->assertSame('text', $field->fresh()->type);
        $this->assertFalse($field->fresh()->is_public);
        $this->post(route('cp.custom-fields.store'), $this->payload(['type' => 'script']))
            ->assertSessionHasErrors('type');
    }

    public function test_select_options_are_required_unique_and_limited(): void
    {
        $this->administrator();
        foreach ([
            ['', 'options'],
            [implode("\n", range(1, 31)), 'options'],
            [str_repeat('x', 121), 'options.0'],
            ["Same\nSame", 'options.0'],
        ] as [$options, $error]) {
            $this->post(route('cp.custom-fields.store'), $this->payload([
                'type' => 'select',
                'options_text' => $options,
            ]))->assertSessionHasErrors($error);
        }
        $this->assertSame(0, LinkCustomField::count());
    }

    public function test_deletion_preserves_existing_link_values_and_defaults_are_private(): void
    {
        $this->administrator();
        $field = LinkCustomField::create(['label' => 'Contact details', 'type' => 'text']);
        $this->assertFalse($field->fresh()->is_public);
        $this->assertTrue($field->fresh()->is_active);
        $value = json_encode([$field->id => 'Saved value'], JSON_THROW_ON_ERROR);
        DB::table('links')->insert(['custom_fields' => $value]);

        $this->delete(route('cp.custom-fields.destroy', $field))->assertRedirect(route('cp.custom-fields.index'));

        $this->assertFalse(LinkCustomField::whereKey($field->id)->exists());
        $this->assertSame($value, DB::table('links')->value('custom_fields'));
    }

    public function test_field_order_uses_the_id_to_break_ties(): void
    {
        $first = LinkCustomField::create(['label' => 'First', 'type' => 'text', 'sort_order' => 2]);
        $second = LinkCustomField::create(['label' => 'Second', 'type' => 'checkbox', 'sort_order' => 2]);
        $leading = LinkCustomField::create(['label' => 'Leading', 'type' => 'number', 'sort_order' => 1]);
        $this->assertSame([$leading->id, $first->id, $second->id], LinkCustomField::ordered()->pluck('id')->all());
    }
}
