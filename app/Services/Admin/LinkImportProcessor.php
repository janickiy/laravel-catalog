<?php

namespace App\Services\Admin;

use App\Enums\LinkStatus;
use App\Helpers\StringHelper;
use App\Repositories\CatalogRepository;
use App\Repositories\LinksRepository;
use App\Services\DomainAvailabilityService;

class LinkImportProcessor
{
    private const DOMAIN_TIMEOUT = 5;

    private int $importedCount = 0;
    private array $categoryCache = [];
    private array $urlCache = [];

    public function __construct(
        private readonly LinksRepository $links,
        private readonly CatalogRepository $catalogs,
        private readonly DomainAvailabilityService $domainAvailability,
    ) {
    }

    public function reset(): void
    {
        $this->importedCount = 0;
        $this->categoryCache = [];
        $this->urlCache = [];
    }

    public function importedCount(): int
    {
        return $this->importedCount;
    }

    public function importRow(array $row): bool
    {
        $row = $this->normalizeRow($row);

        if ($row['url'] === '' || ! $this->domainAvailability->isAvailable($row['url'], self::DOMAIN_TIMEOUT)) {
            return false;
        }

        $url = $this->normalizeUrl($row['url']);

        if ($url === '' || $this->urlExists($url)) {
            return false;
        }

        $tags = $this->metaTags($row['url']);
        $description = $tags['description'] ?? $tags['keywords'] ?? '';

        if ($description === '') {
            return false;
        }

        $this->links->create([
            'name' => $row['name'],
            'url' => $url,
            'phone' => $row['phone'],
            'city' => $row['city'],
            'description' => $description,
            'keywords' => $tags['keywords'] ?? '',
            'full_description' => $description,
            'catalog_id' => $this->catalogId($row['category']),
            'status' => LinkStatus::Published->value,
        ]);

        $this->urlCache[$url] = true;
        $this->importedCount++;

        return true;
    }

    private function normalizeRow(array $row): array
    {
        return [
            'city' => $this->cell($row, 0),
            'name' => $this->cell($row, 1),
            'category' => $this->cell($row, 3),
            'url' => trim((string) ($row[5] ?? '')),
            'phone' => $this->cell($row, 7),
        ];
    }

    private function cell(array $row, int $index): string
    {
        return trim(StringHelper::str_to_utf8((string) ($row[$index] ?? '')));
    }

    private function normalizeUrl(string $url): string
    {
        $url = preg_replace('#^https?://#i', '', trim($url)) ?? '';

        return explode('/', $url, 2)[0] ?? '';
    }

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

    private function metaTags(string $url): array
    {
        $tags = @get_meta_tags($url);

        if (! is_array($tags)) {
            return [];
        }

        return array_map(
            fn ($value) => is_string($value) ? StringHelper::str_to_utf8($value) : $value,
            $tags,
        );
    }

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

    private function findOrCreateCatalog(string $name, ?int $parentId): int
    {
        $cacheKey = mb_strtolower($name, 'UTF-8') . ':' . ($parentId ?? 0);

        if (isset($this->categoryCache[$cacheKey])) {
            return $this->categoryCache[$cacheKey];
        }

        $catalog = $this->catalogs->firstOrCreateByNameAndParent($name, $parentId);

        return $this->categoryCache[$cacheKey] = (int) $catalog->id;
    }
}
