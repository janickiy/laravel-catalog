<?php

namespace App\Helpers;

use App\Models\Settings;

class SettingsHelpers
{
    /**
     * Возвращает значение настройки по ключу или пустую строку.
     */
    public static function getSetting(string $key = ''): string
    {
        $setting = Settings::where('name', $key)->first();

        return $setting?->value ?? '';
    }
}
