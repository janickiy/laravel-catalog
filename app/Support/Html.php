<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

class Html
{
    public static function style(string $url, array $attributes = []): HtmlString
    {
        $attributes = self::attributes(array_merge(['rel' => 'stylesheet', 'href' => asset($url)], $attributes));

        return new HtmlString("<link{$attributes}>");
    }

    public static function script(string $url, array $attributes = []): HtmlString
    {
        $attributes = self::attributes(array_merge(['src' => asset($url)], $attributes));

        return new HtmlString("<script{$attributes}></script>");
    }

    public static function attributes(array $attributes): string
    {
        $html = '';

        foreach ($attributes as $key => $value) {
            if ($value === null || $value === false) {
                continue;
            }

            if ($value === true) {
                $html .= ' ' . e($key);
                continue;
            }

            $html .= ' ' . e($key) . '="' . e((string) $value) . '"';
        }

        return $html;
    }
}
