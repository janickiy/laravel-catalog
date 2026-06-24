<?php

namespace App\Enums;

enum LinkStatus: int
{
    case Pending = 0;
    case Published = 1;
    case Blocked = 2;

    /**
     * Returns the status string code for the legacy representation.
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
     * Returns the human-readable status label.
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
     * Returns the AdminLTE color CSS class for the status.
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
     * Creates an enum status from a string code.
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
     * Normalizes a mixed status value into an enum.
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
     * Returns the string code for any supported status value.
     */
    public static function codeFor(self|int|string|null $status): string
    {
        return self::fromValue($status)?->code() ?? (string) $status;
    }

    /**
     * Returns the label for any supported status value.
     */
    public static function labelFor(self|int|string|null $status): string
    {
        return self::fromValue($status)?->label() ?? (string) $status;
    }

    /**
     * Returns the color CSS class for any supported status value.
     */
    public static function cssColorFor(self|int|string|null $status): string
    {
        return self::fromValue($status)?->cssColor() ?? 'text-bg-secondary';
    }

    /**
     * Returns the status list for select fields.
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
     * Returns the numeric values for all statuses.
     */
    public static function values(): array
    {
        return array_map(
            fn (self $status) => $status->value,
            self::cases(),
        );
    }
}
