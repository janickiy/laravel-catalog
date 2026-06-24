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
    ) {}

    /**
     * Returns the link status list for the bulk change form.
     */
    public function statusList(): array
    {
        return LinkStatus::options();
    }

    /**
     * Returns the catalog category list for the link form.
     */
    public function catalogOptions(): array
    {
        return $this->catalogs->options([0 => '-'.__('interface.common.misc')]);
    }


    /**
     * Creates a link and tries to save the website screenshot.
     *
     * @param LinkData $data
     * @return void
     */
    public function create(LinkData $data): void
    {
        $this->links->createFromData($data->withImage($this->screenshotName($data->url())));
    }

    /**
     * Updates a link and regenerates the screenshot from the URL.
     *
     * @param LinkData $data
     * @return bool
     */
    public function update(LinkData $data): bool
    {
        return $this->links->updateFromData($data->withImage($this->screenshotName($data->url())));
    }

    /**
     * Deletes a link by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->links->delete($id);
    }

    /**
     * Bulk-updates the statuses of the selected links.
     *
     * @param array|null $ids
     * @param int $status
     * @return void
     */
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

    /**
     * Gets the screenshot file name for a URL or an empty string.
     *
     * @param string $url
     * @return string
     */
    private function screenshotName(string $url): string
    {
        $result = FileHelper::getScreenShot($url);

        return $result['name'] ?? '';
    }
}
