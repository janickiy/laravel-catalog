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
     * Делает первый UTF-8 символ строки заглавным.
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
     * Добавляет к URL стандартную схему, если она не указана.
     */
    public static function urlWithPrefix(mixed $url): string
    {
        $url = trim((string) $url);

        return self::hasUrlScheme($url)
            ? $url
            : self::DEFAULT_URL_SCHEME.$url;
    }

    /**
     * Форматирует дату в короткий русский формат d.m.Y.
     */
    public static function date_format_ru(mixed $originalDate): string
    {
        $timestamp = strtotime((string) $originalDate);

        return $timestamp === false ? '' : date('d.m.Y', $timestamp);
    }

    /**
     * Форматирует MySQL-дату русским текстовым месяцем.
     */
    public static function mysql_russian_date(mixed $datestr = '', bool $short = false): string
    {
        return self::formatMysqlDate($datestr, $short, 'ru');
    }

    /**
     * Форматирует MySQL-дату английским текстовым месяцем.
     */
    public static function mysql_english_date(mixed $datestr = '', bool $short = false): string
    {
        return self::formatMysqlDate($datestr, $short, 'en');
    }

    /**
     * Форматирует MySQL-дату текстовым месяцем на текущем языке интерфейса.
     */
    public static function mysql_localized_date(mixed $datestr = '', bool $short = false): string
    {
        return self::formatMysqlDate($datestr, $short, self::currentLocale());
    }

    /**
     * Форматирует MySQL datetime русским текстовым месяцем.
     */
    public static function mysql_russian_datetime(mixed $datestr = '', bool $short = false): string
    {
        return self::formatMysqlDateTime($datestr, $short, 'ru');
    }

    /**
     * Форматирует MySQL datetime английским текстовым месяцем.
     */
    public static function mysql_english_datetime(mixed $datestr = '', bool $short = false): string
    {
        return self::formatMysqlDateTime($datestr, $short, 'en');
    }

    /**
     * Форматирует MySQL datetime текстовым месяцем на текущем языке интерфейса.
     */
    public static function mysql_localized_datetime(mixed $datestr = '', bool $short = false): string
    {
        return self::formatMysqlDateTime($datestr, $short, self::currentLocale());
    }

    /**
     * Обрезает текст до ближайшей границы слова после заданной длины.
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
     * Конвертирует строку из windows-1251 в UTF-8 при необходимости.
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
     * Форматирует размер в байтах в мегабайты с суффиксом.
     */
    public static function formatSizeInMb(int|float|string $size, int $maxDecimals = 3, string $mbSuffix = 'MB'): string
    {
        $mbSize = round((float) $size / 1024 / 1024, $maxDecimals);

        return preg_replace('/\\.?0+$/', '', (string) $mbSize).$mbSuffix;
    }

    /**
     * Определяет максимальный допустимый размер загрузки из ini-настроек PHP.
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
     * Возвращает человекочитаемый максимальный размер загружаемого файла.
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
     * Удаляет HTML-теги и декодирует HTML-сущности в тексте.
     */
    public static function removeHtmlTags(mixed $str): string
    {
        return html_entity_decode(strip_tags((string) $str), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Определяет MIME-тип по расширению через компонент Symfony Mime.
     */
    public static function getMimeType(mixed $ext): string
    {
        $mimeTypes = MimeTypes::getDefault()->getMimeTypes(self::normalizeExtension($ext));

        return $mimeTypes[0] ?? self::DEFAULT_DOWNLOAD_MIME_TYPE;
    }

    /**
     * Проверяет, есть ли у URL HTTP/HTTPS-схема.
     */
    private static function hasUrlScheme(string $url): bool
    {
        return str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
    }

    /**
     * Форматирует только дату MySQL с учетом языка и короткого режима.
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
     * Форматирует дату и время MySQL с учетом языка и короткого режима.
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
     * Извлекает валидную часть даты Y-m-d из MySQL-строки.
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
     * Преобразует MySQL-строку даты и времени в timestamp.
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
     * Возвращает название месяца для нужного языка и формата.
     */
    private static function monthName(int $month, bool $short, string $locale, string $format): string
    {
        return self::MONTHS[$locale][$format][$short ? 'short' : 'full'][$month] ?? '';
    }

    /**
     * Возвращает поддерживаемую текущую локаль для форматирования дат.
     */
    private static function currentLocale(): string
    {
        $locale = app()->getLocale();

        return isset(self::MONTHS[$locale]) ? $locale : 'ru';
    }

    /**
     * Нормализует ini-значение размера в байты.
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
     * Очищает расширение файла от точек, пробелов и лишних символов регистра.
     */
    private static function normalizeExtension(mixed $ext): string
    {
        return trim(strtolower((string) $ext), " \t\n\r\0\x0B.");
    }
}
