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
     * Creates a catalog category DTO from the validated form array.
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
     * Returns the category ID for update operations.
     */
    public function id(): ?int
    {
        return $this->id;
    }

    /**
     * Returns a copy of the DTO with a new image name.
     */
    public function withImage(?string $image): self
    {
        return new self($this->id, $this->name, $this->description, $this->keywords, $this->parentId, $image);
    }

    /**
     * Converts the DTO to a catalog category attribute array.
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
     * Normalizes the parent category ID.
     */
    private static function normalizeParentId(mixed $parentId): ?int
    {
        $parentId = (int) ($parentId ?? 0);

        return $parentId > 0 ? $parentId : null;
    }
}
