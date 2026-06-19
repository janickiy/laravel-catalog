<?php

namespace App\Services\Admin;

use App\DTO\Catalog\CatalogData;
use App\Models\Catalog;
use App\Repositories\CatalogRepository;
use Illuminate\Http\UploadedFile;

class CatalogService
{
    public function __construct(
        private readonly CatalogRepository $catalogs,
        private readonly CatalogImageService $images,
    ) {}

    /**
     * Строит массив разделов, сгруппированный по родительскому разделу.
     */
    public function tree(): array
    {
        $cats = [];

        foreach ($this->catalogs->allOrdered() as $catalog) {
            $cats[$catalog->parent_id ?? 0][$catalog->id] = $catalog->toArray();
        }

        return $cats;
    }

    /**
     * Возвращает HTML-дерево разделов для админки.
     */
    public function treeHtml(): string
    {
        return $this->buildTree($this->tree(), 0) ?? '';
    }

    /**
     * Возвращает список разделов для select-полей.
     */
    public function options(?array $base = null): array
    {
        return $this->catalogs->options($base ?? [0 => __('interface.common.choose')]);
    }

    /**
     * Создает раздел каталога и сохраняет изображение при наличии файла.
     *
     * @param CatalogData $data
     * @param UploadedFile|null $image
     * @return void
     * @throws \Gumlet\ImageResizeException
     */
    public function create(CatalogData $data, ?UploadedFile $image): void
    {
        if ($image !== null) {
            $data = $data->withImage($this->images->store($image));
        }

        $this->catalogs->createFromData($data);
    }


    /**
     * Обновляет раздел каталога и заменяет изображение при загрузке нового файла.
     *
     * @param CatalogData $data
     * @param UploadedFile|null $image
     * @param string|null $oldImage
     * @return bool
     * @throws \Gumlet\ImageResizeException
     */
    public function update(CatalogData $data, ?UploadedFile $image, ?string $oldImage): bool
    {
        if ($image !== null) {
            $this->images->delete($oldImage);
            $data = $data->withImage($this->images->store($image));
        }

        return $this->catalogs->updateFromData($data);
    }

    /**Удаляет раздел каталога, его потомков и связанные изображения
     *
     * @param int $id
     * @return void
     */
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

    /**
     * Рекурсивно собирает HTML для ветки дерева разделов.
     *
     * @param array $catalogs
     * @param int $parentId
     * @return string|null
     */
    private function buildTree(array $catalogs, int $parentId): ?string
    {
        if (! isset($catalogs[$parentId])) {
            return null;
        }

        $tree = '<ul>';

        foreach ($catalogs[$parentId] as $catalog) {
            $tree .= '<li>'.e($catalog['name']).' '.$this->actionsHtml((int) $catalog['id']);
            $tree .= $this->buildTree($catalogs, (int) $catalog['id']);
            $tree .= '</li>';
        }

        return $tree.'</ul>';
    }

    /**
     * Формирует HTML-кнопки действий для строки раздела.
     *
     * @param int $catalogId
     * @return string
     */
    private function actionsHtml(int $catalogId): string
    {
        return '<a title="'.e(__('interface.common.add_subcategory')).'" class="btn btn-xs btn-primary" href="'.route('cp.catalog.create', ['parent_id' => $catalogId]).'"><span class="bi bi-plus-lg"></span></a> '
            .'<a title="'.e(__('interface.common.edit')).'" href="'.route('cp.catalog.edit', ['id' => $catalogId]).'"> <span class="fa fa-pencil"></span> </a> '
            .'<a title="'.e(__('interface.common.delete')).'" href="'.route('cp.catalog.delete', $catalogId).'"> <span class="fa fa-trash-o"></span> </a>';
    }
}
