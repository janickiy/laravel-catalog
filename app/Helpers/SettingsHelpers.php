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
}
