<?php

namespace App\Services\Admin;

use App\DTO\Links\LinkData;
use App\Enums\LinkStatus;
use App\Helpers\FileHelper;
use App\Repositories\CatalogRepository;
use App\Repositories\LinksRepository;

class LinkService
{
    public function __construct(
        private readonly LinksRepository $links,
        private readonly CatalogRepository $catalogs,
    ) {
    }

    public function statusList(): array
    {
        return LinkStatus::options();
    }

    public function catalogOptions(): array
    {
        return $this->catalogs->options([0 => '-Разное'], 0);
    }

    public function create(LinkData $data): void
    {
        $this->links->createFromData($data->withImage($this->screenshotName($data->url())));
    }

    public function update(LinkData $data): bool
    {
        return $this->links->updateFromData($data->withImage($this->screenshotName($data->url())));
    }

    public function delete(int $id): bool
    {
        return $this->links->delete($id);
    }

    public function updateStatuses(?array $ids, int $status): void
    {
        $ids = collect($ids ?? [])
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        if ($ids !== []) {
            $this->links->updateStatuses($ids, $status);
        }
    }

    private function screenshotName(string $url): string
    {
        $result = FileHelper::getScreenShot($url);

        return $result['name'] ?? '';
    }
}
