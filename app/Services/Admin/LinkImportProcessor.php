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
     * Resets the counter and caches before a new import.
     */
    public function reset(): void
    {
        $this->importedCount = 0;
        $this->categoryCache = [];
        $this->urlCache = [];
    }

    /**
     * Returns the number of successfully imported links.
     */
    public function importedCount(): int
    {
        return $this->importedCount;
    }

    /**
     * Validates, normalizes, and saves one row from the imported file.
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
     * Normalizes an imported file row to the expected structure.
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
     * Returns the sanitized UTF-8 cell value by index.
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
     * Cleans a URL down to the domain name without scheme or path.
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
     * Checks URL existence using the local cache.
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
     * Determines the URL column index for the current and legacy import formats.
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
     * Checks whether the cell value looks like a domain or URL.
     */
    private function looksLikeUrl(string $value): bool
    {
        $host = $this->normalizeUrl($value);

        return $host !== '' && str_contains($host, '.');
    }

    /**
     * Builds the link description from imported row data.
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
     * Finds or creates a category chain from the imported path.
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
     * Finds or creates a catalog category and caches the result.
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
