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
     * Создает раздел каталога из DTO.
     */
    public function createFromData(CatalogData $data): Builder|Model
    {
        return $this->create($data->toArray());
    }

    /**
     * Обновляет раздел каталога из DTO.
     */
    public function updateFromData(CatalogData $data): bool
    {
        return $this->update($data->id(), $data->toArray());
    }

    /**
     * Возвращает все разделы каталога, отсортированные по названию.
     */
    public function allOrdered(): Collection
    {
        return $this->model->query()->orderBy('name')->get();
    }

    /**
     * Возвращает дочерние разделы указанного родителя.
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
     * Находит или создает раздел по названию и родителю.
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
     * Возвращает дочерние разделы вместе с количеством связанных ссылок.
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
     * Строит плоский список разделов для select-поля.
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
     * Собирает идентификаторы всех вложенных разделов без зацикливания.
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
     * Возвращает набор разделов по списку идентификаторов.
     *
     * @param array $ids
     * @return Collection
     */
    public function findMany(array $ids): Collection
    {
        return $this->model->query()->whereIn('id', $ids)->get();
    }

    /**
     * Удаляет несколько разделов по списку идентификаторов.
     *
     * @param array $ids
     * @return int
     */
    public function deleteMany(array $ids): int
    {
        return $this->model->query()->whereIn('id', $ids)->delete();
    }

    /**
     * Считает общее количество разделов каталога.
     */
    public function countAll(): int
    {
        return $this->model->query()->count();
    }

    /**
     * Возвращает страницу разделов для XML sitemap.
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
     * Возвращает путь от корня каталога до указанного раздела.
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
