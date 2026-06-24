<?php

namespace App\Helpers;

use Symfony\Component\Mime\MimeTypes;

class StringHelper
{
    private const DEFAULT_URL_SCHEME = 'http://';

    private const DEFAULT_DOWNLOAD_MIME_TYPE = 'application/force-download';

    private const DEFAULT_MAX_UPLOAD_SIZE = 2097152;

    private const MONTHS = [
        'ru' => [
            'date' => [
                'full' => [1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'],
                'short' => [1 => 'янв', 'фев', 'март', 'апр', 'мая', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноябр', 'дек'],
            ],
            'datetime' => [
                'full' => [1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'],
                'short' => [1 => 'янв', 'фев', 'март', 'апр', 'мая', 'июн', 'июл', 'авг', 'сент', 'окт', 'ноябр', 'дек'],
            ],
        ],
        'en' => [
            'date' => [
                'full' => [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                'short' => [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Augt', 'Sept', 'Oct', 'Nov', 'Dec'],
            ],
            'datetime' => [
                'full' => [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                'short' => [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
            ],
        ],
    ];

    private const RELATIVE_DATES = [
        'ru' => [
            'today' => 'Сегодня',
            'yesterday' => 'Вчера',
        ],
        'en' => [
            'today' => 'today',
            'yesterday' => 'yesterday',
        ],
    ];

    /**
     * Uppercases the first UTF-8 character of the string.
     */
    public static function ucfirst_utf8(mixed $str): string
    {
        $str = (string) $str;

        if ($str === '') {
            return '';
        }

        return mb_strtoupper(mb_substr($str, 0, 1, 'UTF-8'), 'UTF-8').mb_substr($str, 1, null, 'UTF-8');
    }

    /**
     * Adds the default scheme to a URL when it is not specified.
     */
    public static function urlWithPrefix(mixed $url): string
    {
        $url = trim((string) $url);

        return self::hasUrlScheme($url)
            ? $url
            : self::DEFAULT_URL_SCHEME.$url;
    }

    /**
     * Formats a date in the short Russian d.m.Y format.
     */
    public static function date_format_ru(mixed $originalDate): string
    {
        $timestamp = strtotime((string) $originalDate);

        return $timestamp === false ? '' : date('d.m.Y', $timestamp);
    }

    /**
     * Formats a MySQL date with a Russian textual month.
     */
    public static function mysql_russian_date(mixed $datestr = '', bool $short = false): string
    {
        return self::formatMysqlDate($datestr, $short, 'ru');
    }

    /**
     * Formats a MySQL date with an English textual month.
     */
    public static function mysql_english_date(mixed $datestr = '', bool $short = false): string
    {
        return self::formatMysqlDate($datestr, $short, 'en');
    }

    /**
     * Formats a MySQL date with a textual month in the current interface language.
     */
    public static function mysql_localized_date(mixed $datestr = '', bool $short = false): string
    {
        return self::formatMysqlDate($datestr, $short, self::currentLocale());
    }

    /**
     * Formats a MySQL datetime with a Russian textual month.
     */
    public static function mysql_russian_datetime(mixed $datestr = '', bool $short = false): string
    {
        return self::formatMysqlDateTime($datestr, $short, 'ru');
    }

    /**
     * Formats a MySQL datetime with an English textual month.
     */
    public static function mysql_english_datetime(mixed $datestr = '', bool $short = false): string
    {
        return self::formatMysqlDateTime($datestr, $short, 'en');
    }

    /**
     * Formats a MySQL datetime with a textual month in the current interface language.
     */
    public static function mysql_localized_datetime(mixed $datestr = '', bool $short = false): string
    {
        return self::formatMysqlDateTime($datestr, $short, self::currentLocale());
    }

    /**
     * Truncates text to the nearest word boundary after the given length.
     */
    public static function shortText(mixed $str, int $chars = 500): string
    {
        $str = (string) $str;
        $chars = max(0, $chars);

        if (mb_strlen($str, 'UTF-8') <= $chars) {
            return $str;
        }

        $tail = mb_substr($str, $chars, null, 'UTF-8');
        $spacePosition = mb_strpos($tail, ' ', 0, 'UTF-8');
        $length = $chars + ($spacePosition === false ? 0 : $spacePosition);

        return mb_substr($str, 0, $length, 'UTF-8').'...';
    }

    /**
     * Converts a string from Windows-1251 to UTF-8 when needed.
     */
    public static function str_to_utf8(mixed $string): string
    {
        $string = (string) $string;

        if (mb_detect_encoding($string, 'UTF-8', true) === false) {
            $converted = @iconv('windows-1251', 'UTF-8', $string);

            return $converted === false ? $string : $converted;
        }

        return $string;
    }

    /**
     * Formats a byte size in megabytes with a suffix.
     */
    public static function formatSizeInMb(int|float|string $size, int $maxDecimals = 3, string $mbSuffix = 'MB'): string
    {
        $mbSize = round((float) $size / 1024 / 1024, $maxDecimals);

        return preg_replace('/\\.?0+$/', '', (string) $mbSize).$mbSuffix;
    }

    /**
     * Determines the maximum allowed upload size from PHP ini settings.
     */
    public static function detectMaxUploadFileSize(): int|float|false
    {
        $limits = [];

        if (($uploadMax = self::normalizeIniSize(ini_get('upload_max_filesize'))) !== false) {
            $limits[] = $uploadMax;
        }

        if (($max_post = self::normalizeIniSize(ini_get('post_max_size'))) !== false && $max_post != 0) {
            $limits[] = $max_post;
        }

        if (($memory_limit = self::normalizeIniSize(ini_get('memory_limit'))) !== false && $memory_limit != -1) {
            $limits[] = $memory_limit;
        }

        return $limits === [] ? false : min($limits);
    }

    /**
     * Returns the human-readable maximum upload size.
     */
    public static function maxUploadFileSize(): string
    {
        $maxUploadFileSize = self::detectMaxUploadFileSize();

        if (! $maxUploadFileSize or $maxUploadFileSize == 0) {
            $maxUploadFileSize = self::DEFAULT_MAX_UPLOAD_SIZE;
        }

        return self::formatSizeInMb($maxUploadFileSize);
    }

    /**
     * Removes HTML tags and decodes HTML entities in text.
     */
    public static function removeHtmlTags(mixed $str): string
    {
        return html_entity_decode(strip_tags((string) $str), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Determines the MIME type by extension through the Symfony Mime component.
     */
    public static function getMimeType(mixed $ext): string
    {
        $mimeTypes = MimeTypes::getDefault()->getMimeTypes(self::normalizeExtension($ext));

        return $mimeTypes[0] ?? self::DEFAULT_DOWNLOAD_MIME_TYPE;
    }

    /**
     * Checks whether the URL has an HTTP/HTTPS scheme.
     */
    private static function hasUrlScheme(string $url): bool
    {
        return str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
    }

    /**
     * Formats only a MySQL date using the language and short-mode settings.
     */
    private static function formatMysqlDate(mixed $datestr, bool $short, string $locale): string
    {
        $date = self::mysqlDatePart($datestr);

        if ($date === null) {
            return '';
        }

        if ($date === date('Y-m-d')) {
            return self::RELATIVE_DATES[$locale]['today'];
        }

        if ($date === date('Y-m-d', strtotime('-1 day'))) {
            return self::RELATIVE_DATES[$locale]['yesterday'];
        }

        [$year, $month, $day] = explode('-', $date);

        return $day.' '.self::monthName((int) $month, $short, $locale, 'date').' '.$year;
    }

    /**
     * Formats a MySQL date and time using the language and short-mode settings.
     */
    private static function formatMysqlDateTime(mixed $datestr, bool $short, string $locale): string
    {
        $timestamp = self::mysqlTimestamp($datestr);

        if ($timestamp === null) {
            return '';
        }

        return date('j', $timestamp).' '
            .self::monthName((int) date('n', $timestamp), $short, $locale, 'datetime').' '
            .date('Y', $timestamp);
    }

    /**
     * Extracts the valid Y-m-d date part from a MySQL string.
     */
    private static function mysqlDatePart(mixed $datestr): ?string
    {
        $datestr = trim((string) $datestr);

        if ($datestr === '') {
            return null;
        }

        [$date] = explode(' ', $datestr);

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) === 1 ? $date : null;
    }

    /**
     * Converts a MySQL datetime string to a timestamp.
     */
    private static function mysqlTimestamp(mixed $datestr): ?int
    {
        $datestr = trim((string) $datestr);

        if ($datestr === '') {
            return null;
        }

        $timestamp = strtotime($datestr);

        return $timestamp === false ? null : $timestamp;
    }

    /**
     * Returns the month name for the requested language and format.
     */
    private static function monthName(int $month, bool $short, string $locale, string $format): string
    {
        return self::MONTHS[$locale][$format][$short ? 'short' : 'full'][$month] ?? '';
    }

    /**
     * Returns the supported current locale for date formatting.
     */
    private static function currentLocale(): string
    {
        $locale = app()->getLocale();

        return isset(self::MONTHS[$locale]) ? $locale : 'ru';
    }

    /**
     * Normalizes an ini size value to bytes.
     */
    private static function normalizeIniSize(string|false $size): int|float|false
    {
        if ($size === false) {
            return false;
        }

        $size = trim($size);

        if (preg_match('/^(-?[\d.]+)(|[KMG])$/i', $size, $match) !== 1) {
            return false;
        }

        $power = array_search(strtoupper($match[2]), ['', 'K', 'M', 'G'], true);

        return (float) $match[1] * 1024 ** $power;
    }

    /**
     * Cleans a file extension from dots, spaces, and extra case differences.
     */
    private static function normalizeExtension(mixed $ext): string
    {
        return trim(strtolower((string) $ext), " \t\n\r\0\x0B.");
    }
}
