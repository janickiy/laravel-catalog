<?php

namespace App\DTO\Admin;

use App\DTO\DataTransferObject;

final readonly class AdminData implements DataTransferObject
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $login,
        private ?string $password = null,
    ) {}

    /**
     * Creates an administrator DTO from the validated form array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['id']) ? (int) $data['id'] : null,
            (string) $data['name'],
            (string) $data['login'],
            isset($data['password']) && $data['password'] !== '' ? (string) $data['password'] : null,
        );
    }

    /**
     * Returns the administrator ID for update operations.
     */
    public function id(): ?int
    {
        return $this->id;
    }

    /**
     * Returns the new administrator password when it was provided.
     */
    public function password(): ?string
    {
        return $this->password;
    }

    /**
     * Converts the DTO to an administrator attribute array.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'login' => $this->login,
        ];
    }
}
