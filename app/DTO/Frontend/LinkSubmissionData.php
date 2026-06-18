<?php

namespace App\DTO\Frontend;

use App\DTO\DataTransferObject;
use App\DTO\Links\LinkData;
use App\Helpers\StringHelper;

final readonly class LinkSubmissionData implements DataTransferObject
{
    public function __construct(
        private string $name,
        private string $url,
        private ?string $city,
        private ?string $email,
        private ?string $phone,
        private string $description,
        private ?string $keywords,
        private string $fullDescription,
        private ?int $catalogId,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string) $data['name'],
            (string) $data['url'],
            $data['city'] ?? null,
            $data['email'] ?? null,
            $data['phone'] ?? null,
            (string) $data['description'],
            $data['keywords'] ?? null,
            (string) $data['full_description'],
            self::normalizeCatalogId($data['catalog_id'] ?? null),
        );
    }

    public function url(): string
    {
        return $this->url;
    }

    public function toLinkData(int $status, ?string $image): LinkData
    {
        return LinkData::fromArray([
            'name' => $this->name,
            'url' => $this->url,
            'city' => $this->city,
            'email' => $this->email,
            'phone' => $this->phone,
            'description' => StringHelper::removeHtmlTags($this->description),
            'keywords' => $this->keywords,
            'full_description' => StringHelper::removeHtmlTags($this->fullDescription),
            'catalog_id' => $this->catalogId,
        ], $status, $image);
    }

    public function toArray(): array
    {
        return [
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
    }

    private static function normalizeCatalogId(mixed $catalogId): ?int
    {
        $catalogId = (int) ($catalogId ?? 0);

        return $catalogId > 0 ? $catalogId : null;
    }
}
