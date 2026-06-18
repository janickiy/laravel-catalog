<?php

namespace App\Enums;

enum LinkStatus: int
{
    case Pending = 0;
    case Published = 1;
    case Blocked = 2;

    public function code(): string
    {
        return match ($this) {
            self::Pending => 'new',
            self::Published => 'publish',
            self::Blocked => 'block',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'ожидает проверку',
            self::Published => 'опубликован',
            self::Blocked => 'в черном списке',
        };
    }

    public static function fromCode(string $code): ?self
    {
        return match ($code) {
            'new' => self::Pending,
            'publish' => self::Published,
            'block', 'black' => self::Blocked,
            default => null,
        };
    }

    public static function fromValue(self|int|string|null $status): ?self
    {
        if ($status instanceof self) {
            return $status;
        }

        if ($status === null || $status === '') {
            return null;
        }

        if (is_numeric($status)) {
            return self::tryFrom((int) $status);
        }

        return self::fromCode((string) $status);
    }

    public static function codeFor(self|int|string|null $status): string
    {
        return self::fromValue($status)?->code() ?? (string) $status;
    }

    public static function labelFor(self|int|string|null $status): string
    {
        return self::fromValue($status)?->label() ?? (string) $status;
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $status) {
            $options[$status->value] = $status->label();
        }

        return $options;
    }

    public static function values(): array
    {
        return array_map(
            fn (self $status) => $status->value,
            self::cases(),
        );
    }
}
