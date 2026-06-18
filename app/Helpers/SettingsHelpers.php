<?php

namespace App\Helpers;

use App\Models\Settings;

class SettingsHelpers
{
    /**
     * @param string $key
     * @return string
     */
    public static function getSetting(string $key = ''): string
    {
        $setting = Settings::where('name',$key)->first();

        if ($setting) {
            return $setting->value;
        } else {
            return '';
        }
    }
}
