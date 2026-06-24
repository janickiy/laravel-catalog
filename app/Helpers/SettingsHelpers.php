<?php

namespace App\Helpers;

use App\Models\Settings;

class SettingsHelpers
{
    /**
     * Returns a setting value by key or an empty string.
     */
    public static function getSetting(string $key = ''): string
    {
        $setting = Settings::where('name', $key)->first();

        return $setting?->value ?? '';
    }

    /**
     * Returns the notification recipient email configured for the catalog.
     */
    public static function notificationEmail(): ?string
    {
        $email = self::getSetting('EMAIL_ADMIN') ?: self::getSetting('EMAIL') ?: config('mail.from.address');
        $email = trim((string) $email);

        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }
}
