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
     * Создает DTO настройки из валидированного массива формы.
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
     * Возвращает идентификатор настройки для операций обновления.
     */
    public function id(): ?int
    {
        return $this->id;
    }

    /**
     * Преобразует DTO в массив атрибутов настройки.
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
