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

    /**
     * Creates a link from a DTO.
     *
     * @param LinkData $data
     * @return Builder|Model
     */
    public function createFromData(LinkData $data): Builder|Model
    {
        return $this->create($data->toArray());
    }

    /**
     * Updates a link from a DTO.
     *
     * @param LinkData $data
     * @return bool
     */
    public function updateFromData(LinkData $data): bool
    {
        return $this->update($data->id(), $data->toArray());
    }


    /**
     * Checks whether a link with the given URL exists.
     *
     * @param string $url
     * @return bool
     */
    public function existsByUrl(string $url): bool
    {
        return $this->model->query()
            ->where('url', $url)
            ->exists();
    }

    /**
     * Returns the query builder for the server-side links table.
     */
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

    /**
     * Counts links with the given status.
     *
     * @param LinkStatus|int $status
     * @return int
     */
    public function countByStatus(LinkStatus|int $status): int
    {
        return $this->model->query()->where('status', $this->statusValue($status))->count();
    }

    /**
     * Counts the total number of links.
     */
    public function countAll(): int
    {
        return $this->model->query()->count();
    }

    /**
     * Returns the latest links for the dashboard.
     *
     * @param int $limit
     * @return Collection
     */
    public function latestForDashboard(int $limit): Collection
    {
        return $this->model->query()
            ->with('catalog')
            ->orderByDesc('id')
            ->take($limit)
            ->get();
    }

    /**
     * Returns the most viewed links.
     *
     * @param int $limit
     * @return Collection
     */
    public function topViewed(int $limit): Collection
    {
        return $this->model->query()
            ->with('catalog')
            ->orderByDesc('views')
            ->orderByDesc('id')
            ->take($limit)
            ->get();
    }

    /**
     * Returns the most viewed published links.
     *
     * @param int $limit
     * @return Collection
     */
    public function topViewedPublished(int $limit): Collection
    {
        return $this->model->query()
            ->where('status', LinkStatus::Published->value)
            ->orderByDesc('views')
            ->orderByDesc('id')
            ->take($limit)
            ->get();
    }

    /**
     * Returns published links for export.
     *
     * @param int|null $catalogId
     * @return Collection
     */
    public function publishedForExport(?int $catalogId): Collection
    {
        $query = $this->model->query()
            ->with('catalog')
            ->where('status', LinkStatus::Published->value)
            ->orderBy('name');

        if ($catalogId !== null) {
            $catalogId > 0
                ? $query->where('catalog_id', $catalogId)
                : $query->whereNull('catalog_id');
        }

        return $query->get();
    }

    /**
     * Bulk-updates the status of the selected links.
     *
     * @param array $ids
     * @param int $status
     * @return int
     */
    public function updateStatuses(array $ids, int $status): int
    {
        return $this->model->query()
            ->whereIn('id', $ids)
            ->update(['status' => $status]);
    }

    /**
     * Returns the latest published links.
     *
     * @param int $limit
     * @return Collection
     */
    public function latestPublished(int $limit): Collection
    {
        return $this->model->query()
            ->where('status', LinkStatus::Published->value)
            ->orderByDesc('id')
            ->take($limit)
            ->get();
    }

    /**
     * Returns the latest published links from the selected categories.
     */

    /**
     * @param array $catalogIds
     * @param int $limit
     * @return Collection
     */
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

    /**
     * Returns a paginated list of published links for the category.
     *
     * @param int|null $catalogId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginatePublishedByCatalog(?int $catalogId, int $perPage): LengthAwarePaginator
    {
        $query = $this->model->query()
            ->where('status', LinkStatus::Published->value);

        $catalogId !== null && $catalogId > 0
            ? $query->where('catalog_id', $catalogId)
            : $query->whereNull('catalog_id');

        return $query->paginate($perPage);
    }

    /**
     * Finds a link for admin viewing together with its category.
     *
     * @param int $id
     * @return Links|null
     */
    public function findForAdmin(int $id): ?Links
    {
        $link = $this->model->query()
            ->with('catalog')
            ->whereKey($id)
            ->first();

        return $link instanceof Links ? $link : null;
    }

    /**
     * Finds a published link for the frontend.
     *
     * @param int $id
     * @return Links|null
     */
    public function findPublished(int $id): ?Links
    {
        $link = $this->model->query()
            ->where('id', $id)
            ->where('status', LinkStatus::Published->value)
            ->first();

        return $link instanceof Links ? $link : null;
    }

    /**
     * Finds a link without filtering by status.
     */
    public function findAny(int $id): ?Links
    {
        $link = $this->find($id);

        return $link instanceof Links ? $link : null;
    }

    /**
     * Returns random published links from the category.
     *
     * @param int|null $catalogId
     * @param int $limit
     * @return Collection
     */
    public function randomPublishedByCatalog(?int $catalogId, int $limit): Collection
    {
        $query = $this->model->query()
            ->where('status', LinkStatus::Published->value)
            ->inRandomOrder()
            ->take($limit);

        $catalogId !== null && $catalogId > 0
            ? $query->where('catalog_id', $catalogId)
            : $query->whereNull('catalog_id');

        return $query->get();
    }

    /**
     * Increments the link view counter.
     *
     * @param Links $link
     * @return bool
     */
    public function incrementViews(Links $link): bool
    {
        return $link->forceFill([
            'views' => (int) $link->views + 1,
        ])->save();
    }

    /**
     * Counts published links.
     */
    public function countPublished(): int
    {
        return $this->model->query()->where('status', LinkStatus::Published->value)->count();
    }

    /**
     * Returns a page of published links for the XML sitemap.
     *
     * @param int $page
     * @return Collection
     */
    public function sitemapPage(int $page): Collection
    {
        $limit = Links::PER_PAGE;

        return $this->model->query()
            ->where('status', LinkStatus::Published->value)
            ->limit($limit)
            ->offset($limit * max(0, $page - 1))
            ->get();
    }

    /**
     * Normalizes an enum or integer status to a numeric value.
     *
     * @param LinkStatus|int $status
     * @return int
     */
    private function statusValue(LinkStatus|int $status): int
    {
        return $status instanceof LinkStatus ? $status->value : $status;
    }
}
