<?php

namespace App\Repositories;

use App\DTO\Links\LinkData;
use App\Enums\LinkStatus;
use App\Models\Links;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LinksRepository extends BaseRepository
{
    public function __construct(Links $model)
    {
        parent::__construct($model);
    }

    public function createFromData(LinkData $data): Builder|Model
    {
        return $this->create($data->toArray());
    }

    public function updateFromData(LinkData $data): bool
    {
        return $this->update($data->id(), $data->toArray());
    }

    public function dataTableQuery(): Builder
    {
        return $this->model->query()
            ->selectRaw('links.id, links.name, links.catalog_id, links.url, links.city, links.phone, links.created_at, links.description, links.status, links.views, catalog.name AS catalog')
            ->leftJoin('catalog', 'catalog.id', '=', 'links.catalog_id')
            ->groupBy('catalog.name')
            ->groupBy('links.id')
            ->groupBy('links.name')
            ->groupBy('links.url')
            ->groupBy('links.phone')
            ->groupBy('links.city')
            ->groupBy('links.created_at')
            ->groupBy('links.status')
            ->groupBy('links.catalog_id')
            ->groupBy('links.views')
            ->groupBy('links.description');
    }

    public function countByStatus(LinkStatus|int $status): int
    {
        return $this->model->query()->where('status', $this->statusValue($status))->count();
    }

    public function publishedForExport(?int $catalogId): Collection
    {
        $query = $this->model->query()
            ->with('catalog')
            ->where('status', LinkStatus::Published->value)
            ->orderBy('name');

        if ($catalogId) {
            $query->where('catalog_id', $catalogId);
        }

        return $query->get();
    }

    public function updateStatuses(array $ids, int $status): int
    {
        return $this->model->query()
            ->whereIn('id', $ids)
            ->update(['status' => $status]);
    }

    public function latestPublished(int $limit): Collection
    {
        return $this->model->query()
            ->where('status', LinkStatus::Published->value)
            ->orderByDesc('id')
            ->take($limit)
            ->get();
    }

    public function latestPublishedInCatalogs(array $catalogIds, int $limit): Collection
    {
        if ($catalogIds === []) {
            return collect();
        }

        return $this->model->query()
            ->where('status', LinkStatus::Published->value)
            ->whereIn('catalog_id', $catalogIds)
            ->orderByDesc('id')
            ->take($limit)
            ->get();
    }

    public function paginatePublishedByCatalog(int $catalogId, int $perPage): LengthAwarePaginator
    {
        return $this->model->query()
            ->where('catalog_id', $catalogId)
            ->where('status', LinkStatus::Published->value)
            ->paginate($perPage);
    }

    public function findPublished(int $id): ?Links
    {
        $link = $this->model->query()
            ->where('id', $id)
            ->where('status', LinkStatus::Published->value)
            ->first();

        return $link instanceof Links ? $link : null;
    }

    public function findAny(int $id): ?Links
    {
        $link = $this->find($id);

        return $link instanceof Links ? $link : null;
    }

    public function randomPublishedByCatalog(int $catalogId, int $limit): Collection
    {
        return $this->model->query()
            ->where('catalog_id', $catalogId)
            ->where('status', LinkStatus::Published->value)
            ->inRandomOrder()
            ->take($limit)
            ->get();
    }

    public function incrementViews(Links $link): bool
    {
        return $link->forceFill([
            'views' => (int) $link->views + 1,
        ])->save();
    }

    public function countPublished(): int
    {
        return $this->model->query()->where('status', LinkStatus::Published->value)->count();
    }

    public function sitemapPage(int $page): Collection
    {
        $limit = Links::PER_PAGE;

        return $this->model->query()
            ->where('status', LinkStatus::Published->value)
            ->limit($limit)
            ->offset($limit * max(0, $page - 1))
            ->get();
    }

    private function statusValue(LinkStatus|int $status): int
    {
        return $status instanceof LinkStatus ? $status->value : $status;
    }
}
