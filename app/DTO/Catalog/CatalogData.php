<?php

namespace App\DTO\Catalog;

use App\DTO\DataTransferObject;

final readonly class CatalogData implements DataTransferObject
{
    public function __construct(
        private ?int $id,
        private string $name,
        private ?string $description,
        private ?string $keywords,
        private ?int $parentId,
        private ?string $image = null,
    ) {}

    /**
     * Создает DTO раздела каталога из валидированного массива формы.
     *
     * @param array $data
     * @param string|null $image
     * @return self
     */
    public static function fromArray(array $data, ?string $image = null): self
    {
        return new self(
            isset($data['id']) ? (int) $data['id'] : null,
            (string) $data['name'],
            $data['description'] ?? null,
            $data['keywords'] ?? null,
            self::normalizeParentId($data['parent_id'] ?? null),
            $image,
        );
    }

    /**
     * Возвращает идентификатор раздела для операций обновления.
     */
    public function id(): ?int
    {
        return $this->id;
    }

    /**
     * Возвращает копию DTO с новым именем изображения.
     */
    public function withImage(?string $image): self
    {
        return new self($this->id, $this->name, $this->description, $this->keywords, $this->parentId, $image);
    }

    /**
     * Преобразует DTO в массив атрибутов раздела каталога.
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'parent_id' => $this->parentId,
        ];

        if ($this->image !== null) {
            $data['image'] = $this->image;
        }

        return $data;
    }

    /**
     * Нормализует идентификатор родительского раздела.
     */
    private static function normalizeParentId(mixed $parentId): ?int
    {
        $parentId = (int) ($parentId ?? 0);

        return $parentId > 0 ? $parentId : null;
    }
}
