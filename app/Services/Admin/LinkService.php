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
     * Возвращает список статусов ссылок для формы массового изменения.
     */
    public function statusList(): array
    {
        return LinkStatus::options();
    }

    /**
     * Возвращает список разделов каталога для формы ссылки.
     */
    public function catalogOptions(): array
    {
        return $this->catalogs->options([0 => '-Разное']);
    }


    /**
     * Создает ссылку и пытается сохранить скриншот сайта.
     *
     * @param LinkData $data
     * @return void
     */
    public function create(LinkData $data): void
    {
        $this->links->createFromData($data->withImage($this->screenshotName($data->url())));
    }

    /**
     * Обновляет ссылку и пересоздает скриншот по URL.
     *
     * @param LinkData $data
     * @return bool
     */
    public function update(LinkData $data): bool
    {
        return $this->links->updateFromData($data->withImage($this->screenshotName($data->url())));
    }

    /**
     * Удаляет ссылку по идентификатору.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->links->delete($id);
    }

    /**
     * Массово обновляет статусы выбранных ссылок.
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
     * Получает имя файла скриншота для URL или пустую строку.
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
