<?php

namespace Database\Seeders;

use App\Enums\LinkStatus;
use App\Models\Catalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DemoLinksSeeder extends Seeder
{
    /**
     * Add fictional websites to the local catalog without changing existing records.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new RuntimeException('Demo links can only be seeded in local or testing environments.');
        }

        $fixtures = json_decode(
            file_get_contents(database_path('seeders/data/demo-links.json')),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );
        $catalogs = Catalog::query()->withCount('children')->get()->keyBy('id');
        $urls = [];

        // Validate all dependencies before inserting any data.
        foreach ($fixtures as $fixture) {
            if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $fixture['slug'])) {
                throw new RuntimeException('Invalid demo slug: '.$fixture['slug']);
            }

            $url = 'https://'.$fixture['slug'].'.catalog-demo.example';

            if (isset($urls[$url])) {
                throw new RuntimeException('Duplicate demo URL: '.$url);
            }

            $urls[$url] = true;
            $catalog = $fixture['catalog_id'] === null ? null : $catalogs->get($fixture['catalog_id']);

            if ($fixture['catalog_id'] !== null && (! $catalog || $catalog->children_count > 0)) {
                throw new RuntimeException('Demo fixture requires a leaf category: '.$fixture['catalog_id']);
            }

            $rootId = $catalog?->parent_id ?? $catalog?->id ?? 0;

            if (! is_file(public_path('uploads/url/demo-catalog-'.$rootId.'.svg'))) {
                throw new RuntimeException('Missing demo preview for category '.$rootId);
            }
        }

        $created = 0;
        $skipped = 0;
        $now = now();
        $total = count($fixtures);

        DB::transaction(function () use ($fixtures, $catalogs, $now, $total, &$created, &$skipped): void {
            foreach ($fixtures as $index => $fixture) {
                $domain = $fixture['slug'].'.catalog-demo.example';
                $url = 'https://'.$domain;

                // The URL column is indexed but not unique, so a MySQL upsert is unsuitable.
                if (DB::table('links')->where('url', $url)->exists()) {
                    $skipped++;

                    continue;
                }

                $catalog = $fixture['catalog_id'] === null ? null : $catalogs->get($fixture['catalog_id']);
                $categoryName = $catalog?->name ?? 'Разное';
                $rootId = $catalog?->parent_id ?? $catalog?->id ?? 0;
                $createdAt = $now->copy()->subHours(($total - $index - 1) * 8 + 1);

                DB::table('links')->insert([
                    'name' => $fixture['name'],
                    'url' => $url,
                    'description' => $fixture['description'],
                    'full_description' => '<p>'.e($fixture['description']).'</p>'
                        .'<p>«'.e($fixture['name']).'» — вымышленный проект из раздела «'.e($categoryName).'». '
                        .'Эта карточка создана для проверки каталога: здесь можно оценить подробное описание, '
                        .'связанные сайты и отображение контактной информации.</p>'
                        .'<p>Адрес на домене .example используется исключительно для демонстрации. '
                        .'Данные не относятся к действующей организации.</p>',
                    'keywords' => 'тестовый сайт, демо, '.mb_strtolower($categoryName),
                    'catalog_id' => $fixture['catalog_id'],
                    'status' => LinkStatus::Published->value,
                    'image' => 'demo-catalog-'.$rootId.'.svg',
                    'views' => 37 + ($index * 137) % 1570,
                    'city' => $index % 3 === 0 ? ['Москва', 'Казань', 'Санкт-Петербург'][intdiv($index, 3) % 3] : null,
                    'email' => $index % 2 === 0 ? 'hello@'.$domain : null,
                    'phone' => null,
                    'comment' => 'catalog-demo-v1',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $created++;
            }
        });

        $this->command?->info("Demo links: {$created} created, {$skipped} already present.");
    }
}
