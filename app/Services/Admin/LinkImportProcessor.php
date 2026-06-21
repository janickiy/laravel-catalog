<?php

namespace App\Services\Admin;

use App\Enums\LinkStatus;
use App\Helpers\StringHelper;
use App\Repositories\CatalogRepository;
use App\Repositories\LinksRepository;

class LinkImportProcessor
{
    private int $importedCount = 0;

    private array $categoryCache = [];

    private array $urlCache = [];

    public function __construct(
        private readonly LinksRepository $links,
        private readonly CatalogRepository $catalogs,
    ) {}

    /**
     * Сбрасывает счетчик и кэши перед новым импортом.
     */
    public function reset(): void
    {
        $this->importedCount = 0;
        $this->categoryCache = [];
        $this->urlCache = [];
    }

    /**
     * Возвращает количество успешно импортированных ссылок.
     */
    public function importedCount(): int
    {
        return $this->importedCount;
    }

    /**
     * Валидирует, нормализует и сохраняет одну строку импортируемого файла.
     *
     * @param array $row
     * @return bool
     */
    public function importRow(array $row): bool
    {
        $row = $this->normalizeRow($row);
        $url = $this->normalizeUrl($row['url']);

        if ($url === '' || $this->urlExists($url)) {
            return false;
        }

        $name = $row['name'] !== '' ? $row['name'] : $url;
        $description = $this->description($row, $name);

        $this->links->create([
            'name' => $name,
            'url' => $url,
            'phone' => $row['phone'],
            'city' => $row['city'],
            'email' => $row['email'],
            'description' => $description,
            'keywords' => $row['category'],
            'full_description' => $description,
            'catalog_id' => $this->catalogId($row['category']),
            'status' => LinkStatus::Published->value,
        ]);

        $this->urlCache[$url] = true;
        $this->importedCount++;

        return true;
    }

    /**
     * Приводит строку импортируемого файла к ожидаемой структуре.
     *
     * @param array $row
     * @return array
     */
    private function normalizeRow(array $row): array
    {
        $urlIndex = $this->urlIndex($row);

        if ($urlIndex === 3) {
            return [
                'city' => $this->cell($row, 0),
                'name' => $this->cell($row, 1),
                'category' => $this->cell($row, 2),
                'url' => $this->cell($row, 3),
                'phone' => $this->cell($row, 4),
                'email' => $this->cell($row, 5),
            ];
        }

        return [
            'city' => $this->cell($row, 0),
            'name' => $this->cell($row, 1),
            'category' => $this->cell($row, 3),
            'url' => $this->cell($row, 5),
            'phone' => $this->cell($row, 7),
            'email' => $this->cell($row, 8),
        ];
    }

    /**
     * Возвращает очищенное UTF-8 значение ячейки по индексу.
     *
     * @param array $row
     * @param int $index
     * @return string
     */
    private function cell(array $row, int $index): string
    {
        return trim(StringHelper::str_to_utf8((string) ($row[$index] ?? '')));
    }

    /**
     * Очищает URL до доменного имени без схемы и пути.
     *
     * @param string $url
     * @return string
     */
    private function normalizeUrl(string $url): string
    {
        $url = trim(StringHelper::str_to_utf8($url));

        if ($url === '') {
            return '';
        }

        if (! preg_match('#^https?://#i', $url)) {
            $url = 'http://'.$url;
        }

        $host = parse_url($url, PHP_URL_HOST);

        return is_string($host) ? mb_strtolower(trim($host), 'UTF-8') : '';
    }

    /**
     * Проверяет существование URL с использованием локального кэша.
     *
     * @param string $url
     * @return bool
     */
    private function urlExists(string $url): bool
    {
        if (isset($this->urlCache[$url])) {
            return true;
        }

        if ($this->links->existsByUrl($url)) {
            $this->urlCache[$url] = true;

            return true;
        }

        return false;
    }

    /**
     * Определяет индекс колонки с URL для текущего и legacy-формата импорта.
     */
    private function urlIndex(array $row): int
    {
        foreach ([3, 5] as $index) {
            if ($this->looksLikeUrl($this->cell($row, $index))) {
                return $index;
            }
        }

        foreach ($row as $index => $value) {
            if (is_int($index) && $this->looksLikeUrl($this->cell($row, $index))) {
                return $index;
            }
        }

        return 5;
    }

    /**
     * Проверяет, похоже ли значение ячейки на домен или URL.
     */
    private function looksLikeUrl(string $value): bool
    {
        $host = $this->normalizeUrl($value);

        return $host !== '' && str_contains($host, '.');
    }

    /**
     * Собирает описание ссылки из данных импортируемой строки.
     */
    private function description(array $row, string $name): string
    {
        $parts = array_filter([
            $name,
            $row['category'],
            $row['city'],
        ]);

        return implode('. ', $parts);
    }

    /**
     * Находит или создает цепочку разделов из импортируемого пути.
     *
     * @param string $path
     * @return int|null
     */
    private function catalogId(string $path): ?int
    {
        $parts = array_values(array_filter(array_map('trim', explode('/', $path))));
        $parentId = null;
        $catalogId = null;

        foreach ($parts as $name) {
            $catalogId = $this->findOrCreateCatalog($name, $parentId);
            $parentId = $catalogId;
        }

        return $catalogId;
    }

    /**
     * Находит или создает раздел каталога с кэшированием результата.
     *
     * @param string $name
     * @param int|null $parentId
     * @return int
     */
    private function findOrCreateCatalog(string $name, ?int $parentId): int
    {
        $cacheKey = mb_strtolower($name, 'UTF-8').':'.($parentId ?? 0);

        if (isset($this->categoryCache[$cacheKey])) {
            return $this->categoryCache[$cacheKey];
        }

        $catalog = $this->catalogs->firstOrCreateByNameAndParent($name, $parentId);

        return $this->categoryCache[$cacheKey] = (int) $catalog->id;
    }
}
