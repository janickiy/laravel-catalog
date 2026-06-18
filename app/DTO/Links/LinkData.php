<?php

namespace App\DTO\Links;

use App\DTO\DataTransferObject;

final readonly class LinkData implements DataTransferObject
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $url,
        private ?string $city,
        private ?string $email,
        private ?string $phone,
        private string $description,
        private ?string $keywords,
        private string $fullDescription,
        private ?int $catalogId,
        private ?int $status = null,
        private ?string $image = null,
    ) {
    }

    public static function fromArray(array $data, ?int $status = null, ?string $image = null): self
    {
        return new self(
            isset($data['id']) ? (int) $data['id'] : null,
            (string) $data['name'],
            (string) $data['url'],
            $data['city'] ?? null,
            $data['email'] ?? null,
            $data['phone'] ?? null,
            (string) $data['description'],
            $data['keywords'] ?? null,
            (string) $data['full_description'],
            self::normalizeCatalogId($data['catalog_id'] ?? null),
            $status,
            $image,
        );
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function withImage(?string $image): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->url,
            $this->city,
            $this->email,
            $this->phone,
            $this->description,
            $this->keywords,
            $this->fullDescription,
            $this->catalogId,
            $this->status,
            $image,
        );
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'url' => $this->url,
            'city' => $this->city,
            'email' => $this->email,
            'phone' => $this->phone,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'full_description' => $this->fullDescription,
            'catalog_id' => $this->catalogId,
        ];

        if ($this->status !== null) {
            $data['status'] = $this->status;
        }

        if ($this->image !== null) {
            $data['image'] = $this->image;
        }

        return $data;
    }

    private static function normalizeCatalogId(mixed $catalogId): ?int
    {
        $catalogId = (int) ($catalogId ?? 0);

        return $catalogId > 0 ? $catalogId : null;
    }
}
