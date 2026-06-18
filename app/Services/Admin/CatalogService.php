<?php

namespace App\Services\Admin;

use App\DTO\Catalog\CatalogData;
use App\Models\Catalog;
use App\Repositories\CatalogRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;

class CatalogService
{
    public function __construct(
        private readonly CatalogRepository $catalogs,
        private readonly CatalogImageService $images,
    ) {
    }

    public function tree(): array
    {
        $cats = [];

        foreach ($this->catalogs->allOrdered() as $catalog) {
            $cats[$catalog->parent_id ?? 0][$catalog->id] = $catalog->toArray();
        }

        return $cats;
    }

    public function treeHtml(): string
    {
        return $this->buildTree($this->tree(), 0) ?? '';
    }

    public function options(array $base = [0 => 'Выберите']): array
    {
        return $this->catalogs->options($base);
    }

    public function create(CatalogData $data, ?UploadedFile $image): void
    {
        if ($image !== null) {
            $data = $data->withImage($this->images->store($image));
        }

        $this->catalogs->createFromData($data);
    }

    public function update(CatalogData $data, ?UploadedFile $image, ?string $oldImage): bool
    {
        if ($image !== null) {
            $this->images->delete($oldImage);
            $data = $data->withImage($this->images->store($image));
        }

        return $this->catalogs->updateFromData($data);
    }

    public function deleteWithChildren(int $id): void
    {
        /** @var Catalog $catalog */
        $catalog = $this->catalogs->find($id);
        abort_if(! $catalog, 404);

        $ids = array_merge($this->catalogs->descendantIds($catalog), [$id]);
        $catalogs = $this->catalogs->findMany($ids);

        foreach ($catalogs->pluck('image') as $image) {
            $this->images->delete($image);
        }

        $this->catalogs->deleteMany($ids);
    }

    private function buildTree(array $catalogs, int $parentId): ?string
    {
        if (! isset($catalogs[$parentId])) {
            return null;
        }

        $tree = '<ul>';

        foreach ($catalogs[$parentId] as $catalog) {
            $tree .= '<li>' . e($catalog['name']) . ' ' . $this->actionsHtml((int) $catalog['id']);
            $tree .= $this->buildTree($catalogs, (int) $catalog['id']);
            $tree .= '</li>';
        }

        return $tree . '</ul>';
    }

    private function actionsHtml(int $catalogId): string
    {
        return '<a title="Добавить подкатегорию" href="' . URL::route('cp.catalog.create', ['parent_id' => $catalogId]) . '"> <span class="fa fa-plus"></span> </a> '
            . '<a title="Редактировать" href="' . URL::route('cp.catalog.edit', ['id' => $catalogId]) . '"> <span class="fa fa-pencil"></span> </a> '
            . '<a title="Удалить" href="' . URL::route('cp.catalog.delete', $catalogId) . '"> <span class="fa fa-trash-o"></span> </a>';
    }
}
