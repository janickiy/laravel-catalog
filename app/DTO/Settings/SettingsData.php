<?php

namespace App\DTO\Settings;

use App\DTO\DataTransferObject;

final readonly class SettingsData implements DataTransferObject
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $value,
        private ?string $description,
    ) {}

    /**
     * Creates a setting DTO from the validated form array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['id']) ? (int) $data['id'] : null,
            (string) $data['name'],
            (string) $data['value'],
            $data['description'] ?? null,
        );
    }

    /**
     * Returns the setting ID for update operations.
     */
    public function id(): ?int
    {
        return $this->id;
    }

    /**
     * Converts the DTO to a setting attribute array.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'description' => $this->description,
        ];
    }
}
