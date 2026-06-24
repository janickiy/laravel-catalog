<?php

namespace App\Repositories;

use App\DTO\Catalog\CatalogData;
use App\Models\Catalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CatalogRepository extends BaseRepository
{
    public function __construct(Catalog $model)
    {
        parent::__construct($model);
    }

    /**
     * Creates a catalog category from a DTO.
     */
    public function createFromData(CatalogData $data): Builder|Model
    {
        return $this->create($data->toArray());
    }

    /**
     * Updates a catalog category from a DTO.
     */
    public function updateFromData(CatalogData $data): bool
    {
        return $this->update($data->id(), $data->toArray());
    }

    /**
     * Returns all catalog categories sorted by name.
     */
    public function allOrdered(): Collection
    {
        return $this->model->query()->orderBy('name')->get();
    }

    /**
     * Returns the child categories for the given parent.
     */
    public function childrenOf(?int $parentId): EloquentCollection
    {
        $query = $this->model->query();

        $parentId === null
            ? $query->whereNull('parent_id')
            : $query->where('parent_id', $parentId);

        return $query->orderBy('name')->get();
    }

    /**
     * Finds or creates a category by name and parent.
     *
     * @param string $name
     * @param int|null $parentId
     * @return Catalog
     */
    public function firstOrCreateByNameAndParent(string $name, ?int $parentId): Catalog
    {
        return $this->model->query()->firstOrCreate([
            'name' => $name,
            'parent_id' => $parentId,
        ]);
    }

    /**
     * Returns child categories with their related link counts.
     *
     * @param int|null $parentId
     * @return Collection
     */
    public function childrenWithLinkCounts(?int $parentId): Collection
    {
        $query = $this->model->query()
            ->selectRaw('catalog.name, catalog.id, catalog.image, COUNT(links.status) AS number_links')
            ->leftJoin('links', 'links.catalog_id', '=', 'catalog.id')
            ->groupBy('catalog.id')
            ->groupBy('catalog.name')
            ->groupBy('catalog.image')
            ->orderBy('catalog.name');

        $parentId === null
            ? $query->whereNull('catalog.parent_id')
            : $query->where('catalog.parent_id', $parentId);

        return $query->get();
    }

    /**
     * Builds a flat category list for a select field.
     *
     * @param array $options
     * @param int|null $parentId
     * @param int $level
     * @return array
     */
    public function options(array $options, ?int $parentId = null, int $level = 0): array
    {
        $level++;

        foreach ($this->childrenOf($parentId) as $row) {
            $options[$row->id] = str_repeat('-', max(0, $level - 1)).' '.$row->name;
            $options = $this->options($options, $row->id, $level);
        }

        return $options;
    }

    /**
     * Collects all nested category IDs without cycles.
     *
     * @param Catalog $catalog
     * @param array $visited
     * @return array
     */
    public function descendantIds(Catalog $catalog, array $visited = []): array
    {
        if (isset($visited[$catalog->id])) {
            return [];
        }

        $visited[$catalog->id] = true;
        $ids = [];

        foreach ($catalog->children as $child) {
            if (isset($visited[$child->id])) {
                continue;
            }

            $ids[] = $child->id;
            $ids = array_merge($ids, $this->descendantIds($child, $visited));
        }

        return $ids;
    }

    /**
     * Returns a category collection by a list of IDs.
     *
     * @param array $ids
     * @return Collection
     */
    public function findMany(array $ids): Collection
    {
        return $this->model->query()->whereIn('id', $ids)->get();
    }

    /**
     * Deletes multiple categories by a list of IDs.
     *
     * @param array $ids
     * @return int
     */
    public function deleteMany(array $ids): int
    {
        return $this->model->query()->whereIn('id', $ids)->delete();
    }

    /**
     * Counts the total number of catalog categories.
     */
    public function countAll(): int
    {
        return $this->model->query()->count();
    }

    /**
     * Returns a page of categories for the XML sitemap.
     *
     * @param int $page
     * @return Collection
     */
    public function sitemapPage(int $page): Collection
    {
        $limit = Catalog::PER_PAGE;

        return $this->model->query()
            ->limit($limit)
            ->offset($limit * max(0, $page - 1))
            ->get();
    }

    /**
     * Returns the path from the catalog root to the given category.
     *
     * @param int $id
     * @return array
     */
    public function pathToRoot(int $id): array
    {
        $path = [];
        $visited = [];
        $currentId = $id;

        while ($currentId > 0) {
            if (isset($visited[$currentId])) {
                break;
            }

            $visited[$currentId] = true;
            $catalog = $this->find($currentId);

            if (! $catalog instanceof Catalog) {
                break;
            }

            array_unshift($path, [$catalog->id, $catalog->name]);
            $currentId = $catalog->parent_id === null ? 0 : (int) $catalog->parent_id;
        }

        return $path;
    }
}
