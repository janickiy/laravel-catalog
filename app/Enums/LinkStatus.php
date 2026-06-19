<?php

namespace App\Enums;

enum LinkStatus: int
{
    case Pending = 0;
    case Published = 1;
    case Blocked = 2;

    /**
     * Возвращает строковый код статуса для legacy-представления.
     */
    public function code(): string
    {
        return match ($this) {
            self::Pending => 'new',
            self::Published => 'publish',
            self::Blocked => 'block',
        };
    }

    /**
     * Возвращает человекочитаемую подпись статуса.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => __('interface.link_status.pending'),
            self::Published => __('interface.link_status.published'),
            self::Blocked => __('interface.link_status.blocked'),
        };
    }

    /**
     * Возвращает AdminLTE CSS-класс цвета для статуса.
     */
    public function cssColor(): string
    {
        return match ($this) {
            self::Pending => 'text-bg-success',
            self::Published => 'text-bg-primary',
            self::Blocked => 'text-bg-danger',
        };
    }

    /**
     * Создает enum-статус из строкового кода.
     */
    public static function fromCode(string $code): ?self
    {
        return match ($code) {
            'new' => self::Pending,
            'publish' => self::Published,
            'block', 'black' => self::Blocked,
            default => null,
        };
    }

    /**
     * Нормализует смешанное значение статуса в enum.
     */
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

    /**
     * Возвращает строковый код для любого поддерживаемого значения статуса.
     */
    public static function codeFor(self|int|string|null $status): string
    {
        return self::fromValue($status)?->code() ?? (string) $status;
    }

    /**
     * Возвращает подпись для любого поддерживаемого значения статуса.
     */
    public static function labelFor(self|int|string|null $status): string
    {
        return self::fromValue($status)?->label() ?? (string) $status;
    }

    /**
     * Возвращает CSS-класс цвета для любого поддерживаемого значения статуса.
     */
    public static function cssColorFor(self|int|string|null $status): string
    {
        return self::fromValue($status)?->cssColor() ?? 'text-bg-secondary';
    }

    /**
     * Возвращает список статусов для select-полей.
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $status) {
            $options[$status->value] = $status->label();
        }

        return $options;
    }

    /**
     * Возвращает числовые значения всех статусов.
     */
    public static function values(): array
    {
        return array_map(
            fn (self $status) => $status->value,
            self::cases(),
        );
    }
}
