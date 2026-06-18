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
    ) {
    }

    public function tree(): array
    {
        $cats = [];

        foreach ($this->catalogs->all() as $catalog) {
            $cats[$catalog->parent_id][$catalog->id] = $catalog->toArray();
        }

        return $cats;
    }

    public function options(array $base = [0 => 'Выберите']): array
    {
        return $this->catalogs->options($base, 0);
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
}
