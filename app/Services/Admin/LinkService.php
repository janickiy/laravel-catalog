<?php

namespace App\Services\Admin;

use App\DTO\Links\LinkData;
use App\Enums\LinkStatus;
use App\Helpers\FileHelper;
use App\Repositories\CatalogRepository;
use App\Repositories\LinksRepository;
use App\Services\Links\LinkScreenshotService;
use Illuminate\Http\UploadedFile;

class LinkService
{
    public function __construct(
        private readonly LinksRepository $links,
        private readonly CatalogRepository $catalogs,
        private readonly LinkScreenshotService $screenshots,
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
    public function create(LinkData $data, ?UploadedFile $screenshot = null): void
    {
        $image = $screenshot ? $this->screenshots->store($screenshot) : $this->screenshotName($data->url());
        try {
            $this->links->createFromData($data->withImage($image));
        } catch (\Throwable $exception) {
            if ($screenshot) {
                $this->screenshots->delete($image);
            }
            throw $exception;
        }
    }

    /**
     * Updates a link, preserving its screenshot unless explicitly replaced or removed.
     *
     * @param LinkData $data
     * @return bool
     */
    public function update(LinkData $data, ?UploadedFile $screenshot = null, bool $remove = false): bool
    {
        $link = $this->links->findAny($data->id());
        if (! $link) {
            return false;
        }

        $previousImage = $link->image;
        $image = $screenshot ? $this->screenshots->store($screenshot) : ($remove ? '' : $previousImage);
        try {
            $saved = $this->links->updateFromData($data->withImage($image));
        } catch (\Throwable $exception) {
            if ($screenshot) {
                $this->screenshots->delete($image);
            }
            throw $exception;
        }

        if ($saved && $image !== $previousImage) {
            $this->screenshots->delete($previousImage);
        } elseif (! $saved && $screenshot) {
            $this->screenshots->delete($image);
        }

        return $saved;
    }

    /**
     * Deletes a link by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $link = $this->links->findAny($id);
        $deleted = $this->links->delete($id);
        if ($deleted && $link) {
            $this->screenshots->delete($link->image);
        }

        return $deleted;
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
