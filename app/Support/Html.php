<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

class Html
{
    /**
     * Формирует HTML-тег подключения stylesheet.
     *
     * @param string $url
     * @param array $attributes
     * @return HtmlString
     */
    public static function style(string $url, array $attributes = []): HtmlString
    {
        $attributes = self::attributes(array_merge(['rel' => 'stylesheet', 'href' => asset($url)], $attributes));

        return new HtmlString("<link{$attributes}>");
    }

    /**
     * Формирует HTML-тег подключения script.
     *
     * @param string $url
     * @param array $attributes
     * @return HtmlString
     */
    public static function script(string $url, array $attributes = []): HtmlString
    {
        $attributes = self::attributes(array_merge(['src' => asset($url)], $attributes));

        return new HtmlString("<script{$attributes}></script>");
    }

    /**
     * Преобразует массив атрибутов в безопасную HTML-строку
     *
     * @param array $attributes
     * @return string
     */
    public static function attributes(array $attributes): string
    {
        $html = '';

        foreach ($attributes as $key => $value) {
            if ($value === null || $value === false) {
                continue;
            }

            if ($value === true) {
                $html .= ' '.e($key);

                continue;
            }

            $html .= ' '.e($key).'="'.e((string) $value).'"';
        }

        return $html;
    }
}
