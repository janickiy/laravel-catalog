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
     * @param $str
     * @return string
     */
    public static function ucfirst_utf8($str)
    {
        $str = (string) $str;

        if ($str === '') {
            return '';
        }

        return mb_strtoupper(mb_substr($str, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($str, 1, null, 'UTF-8');
    }

    /**
     * @param $url
     * @return mixed|string
     */
    public static function urlWithPrefix($url)
    {
        $url = trim((string) $url);

        return self::hasUrlScheme($url)
            ? $url
            : self::DEFAULT_URL_SCHEME . $url;
    }

    /**
     * @param $originalDate
     * @return false|string
     */
    public static function date_format_ru($originalDate)
    {
        $timestamp = strtotime((string) $originalDate);

        return $timestamp === false ? '' : date('d.m.Y', $timestamp);
    }

    /**
     * @param string $datestr
     * @param bool $short
     * @return string
     */
    public static function mysql_russian_date($datestr = '', $short = false)
    {
        return self::formatMysqlDate($datestr, (bool) $short, 'ru');
    }

    /**
     * @param string $datestr
     * @param bool $short
     * @return string
     */
    public static function mysql_english_date($datestr = '', $short = false)
    {
        return self::formatMysqlDate($datestr, (bool) $short, 'en');
    }

    /**
     * @param string $datestr
     * @param bool $short
     * @return string
     */
    public static function mysql_russian_datetime($datestr = '', $short = false)
    {
        return self::formatMysqlDateTime($datestr, (bool) $short, 'ru');
    }

    /**
     * @param string $datestr
     * @param bool $short
     * @return string
     */
    public static function mysql_english_datetime($datestr = '', $short = false)
    {
        return self::formatMysqlDateTime($datestr, (bool) $short, 'en');
    }

    /**
     * @param $str
     * @param int $chars
     * @return string
     */
    public static function shortText($str, $chars = 500)
    {
        $str = (string) $str;
        $chars = max(0, (int) $chars);

        if (mb_strlen($str, 'UTF-8') <= $chars) {
            return $str;
        }

        $tail = mb_substr($str, $chars, null, 'UTF-8');
        $spacePosition = mb_strpos($tail, ' ', 0, 'UTF-8');
        $length = $chars + ($spacePosition === false ? 0 : $spacePosition);

        return mb_substr($str, 0, $length, 'UTF-8') . '...';
    }

    /**
     * @param $string
     * @return false|mixed|string
     */
    public static function str_to_utf8($string)
    {
        if (mb_detect_encoding($string, 'UTF-8', true) === false) {
            $converted = @iconv('windows-1251', 'UTF-8', $string);

            return $converted === false ? $string : $converted;
        }

        return $string;
    }

    /**
     * @param $size
     * @param int $maxDecimals
     * @param string $mbSuffix
     * @return string
     */
    public static function formatSizeInMb($size, $maxDecimals = 3, $mbSuffix = "MB")
    {
        $mbSize = round((float) $size / 1024 / 1024, (int) $maxDecimals);

        return preg_replace('/\\.?0+$/', '', (string) $mbSize) . $mbSuffix;
    }

    /**
     * @return mixed
     */
    public static function detectMaxUploadFileSize()
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
     * @return string
     */
    public static function maxUploadFileSize()
    {
        $maxUploadFileSize = self::detectMaxUploadFileSize();

        if (!$maxUploadFileSize or $maxUploadFileSize == 0) {
            $maxUploadFileSize = self::DEFAULT_MAX_UPLOAD_SIZE;
        }

        return self::formatSizeInMb($maxUploadFileSize);
    }

    /**
     * @param $str
     * @return string
     */
    public static function removeHtmlTags($str)
    {
        return html_entity_decode(strip_tags((string) $str), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * @param $ext
     * @return mixed|string
     */
    public static function getMimeType($ext)
    {
        $mimeTypes = MimeTypes::getDefault()->getMimeTypes(self::normalizeExtension($ext));

        return $mimeTypes[0] ?? self::DEFAULT_DOWNLOAD_MIME_TYPE;
    }

    private static function hasUrlScheme(string $url): bool
    {
        return str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
    }

    private static function formatMysqlDate($datestr, bool $short, string $locale): string
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

        return $day . ' ' . self::monthName((int) $month, $short, $locale, 'date') . ' ' . $year;
    }

    private static function formatMysqlDateTime($datestr, bool $short, string $locale): string
    {
        $timestamp = self::mysqlTimestamp($datestr);

        if ($timestamp === null) {
            return '';
        }

        return date('j', $timestamp) . ' '
            . self::monthName((int) date('n', $timestamp), $short, $locale, 'datetime') . ' '
            . date('Y', $timestamp);
    }

    private static function mysqlDatePart($datestr): ?string
    {
        $datestr = trim((string) $datestr);

        if ($datestr === '') {
            return null;
        }

        [$date] = explode(' ', $datestr);

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) === 1 ? $date : null;
    }

    private static function mysqlTimestamp($datestr): ?int
    {
        $datestr = trim((string) $datestr);

        if ($datestr === '') {
            return null;
        }

        $timestamp = strtotime($datestr);

        return $timestamp === false ? null : $timestamp;
    }

    private static function monthName(int $month, bool $short, string $locale, string $format): string
    {
        return self::MONTHS[$locale][$format][$short ? 'short' : 'full'][$month] ?? '';
    }

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

    private static function normalizeExtension($ext): string
    {
        return trim(strtolower((string) $ext), " \t\n\r\0\x0B.");
    }

}
