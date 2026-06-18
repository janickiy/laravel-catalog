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
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['id']) ? (int) $data['id'] : null,
            (string) $data['name'],
            (string) $data['login'],
            isset($data['password']) && $data['password'] !== '' ? (string) $data['password'] : null,
        );
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function password(): ?string
    {
        return $this->password;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'login' => $this->login,
        ];
    }
}
