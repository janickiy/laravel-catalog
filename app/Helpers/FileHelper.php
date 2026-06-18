<?php

namespace App\Helpers;

use Gumlet\ImageResize;
use Illuminate\Support\Facades\Storage;
use Throwable;

class FileHelper
{
    private const DEFAULT_SCHEME = 'http://';

    private const DEFAULT_TIMEOUT = 10;

    private const PAGE_SPEED_ENDPOINT = 'https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed';

    private const MINI_SCREENSHOT_ENDPOINT = 'http://mini.s-shot.ru';

    private const SCREENSHOT_DIRECTORY = '/uploads/url';

    private const LINKS_STORAGE_DIRECTORY = 'url';

    /**
     * Получает полноразмерный скриншот сайта через PageSpeed API и сохраняет его.
     *
     * @param string $url
     * @return array|false[]
     */
    public static function getScreenShot(string $url): array
    {
        $response = self::fetchUrl(self::pageSpeedUrl($url));

        if ($response === false) {
            return self::failedResult();
        }

        $screenshot = self::extractPageSpeedScreenshot($response);

        if ($screenshot === null) {
            return self::failedResult();
        }

        return self::savePageSpeedScreenshot($screenshot);
    }

    /**
     * Получает миниатюру скриншота через внешний сервис и сохраняет ее.
     *
     * @param string $url
     * @param string $screen
     * @param string $size
     * @param string $format
     * @return array|false[]
     */
    public static function getScreenShotMini(string $url, string $screen, string $size, string $format = 'jpg'): array
    {
        $response = self::fetchUrl(self::miniScreenshotUrl($url, $screen, $size, $format));

        if ($response === false) {
            return self::failedResult();
        }

        return self::saveMiniScreenshot($response, $format);
    }

    /**
     * Возвращает содержимое URL с указанным таймаутом.
     *
     * @param string $url
     * @param int $timeout
     * @return bool|string
     */
    public static function getDataContents(string $url, int $timeout = self::DEFAULT_TIMEOUT): bool|string
    {
        return self::fetchUrl($url, $timeout);
    }

    /**
     * Проверяет, является ли строка корректным URL после добавления схемы.
     *
     * @param string $url
     * @return bool
     */
    public static function url_exists(string $url): bool
    {
        $normalizedUrl = self::urlWithScheme($url);
        $curl = curl_init($normalizedUrl);

        if ($curl === false) {
            return false;
        }

        return filter_var($normalizedUrl, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Формирует URL запроса к PageSpeed API для получения скриншота
     *
     * @param string $url
     * @return string
     */
    private static function pageSpeedUrl(string $url): string
    {
        return self::PAGE_SPEED_ENDPOINT.'?'.http_build_query([
            'url' => self::urlWithScheme($url),
            'category' => 'CATEGORY_UNSPECIFIED',
            'strategy' => 'DESKTOP',
            'key' => env('GOOGLE_API_KEY'),
        ]);
    }

    /**
     * Формирует URL запроса к сервису миниатюр скриншотов.
     *
     * @param string $url
     * @param string $screen
     * @param string $size
     * @param string $format
     * @return string
     */
    private static function miniScreenshotUrl(string $url, string $screen, string $size, string $format): string
    {
        return implode('/', [
            self::MINI_SCREENSHOT_ENDPOINT,
            rawurlencode($screen),
            rawurlencode($size),
            rawurlencode($format),
        ]).'/?'.self::urlWithScheme($url);
    }

    /**
     * Добавляет URL стандартную схему, если она отсутствует.
     *
     * @param string $url
     * @return string
     */
    private static function urlWithScheme(string $url): string
    {
        $url = trim($url);

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return self::DEFAULT_SCHEME.$url;
    }

    /**
     * Выполняет curl-запрос и возвращает тело ответа.
     *
     * @param string $url
     * @param int $timeout
     * @return bool|string
     */
    private static function fetchUrl(string $url, int $timeout = self::DEFAULT_TIMEOUT): bool|string
    {
        $curl = curl_init($url);

        if ($curl === false) {
            return false;
        }

        curl_setopt_array($curl, [
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER => false,
            CURLOPT_REFERER => $_SERVER['HTTP_REFERER'] ?? '',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'] ?? 'Laravel Catalog',
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    /**
     * Извлекает base64-данные скриншота из ответа PageSpeed API.
     *
     * @param string $response
     * @return string|null
     */
    private static function extractPageSpeedScreenshot(string $response): ?string
    {
        $data = json_decode($response, true);

        if (! is_array($data)) {
            return null;
        }

        $screenshot = $data['lighthouseResult']['audits']['full-page-screenshot']['details']['screenshot']['data'] ?? null;

        if (! is_string($screenshot) || $screenshot === '') {
            return null;
        }

        return preg_replace('/^data:image\/[a-zA-Z0-9.+-]+;base64,/', '', $screenshot);
    }


    /**
     * Декодирует и сохраняет скриншот PageSpeed в локальную директорию.
     *
     * @param string $base64Image
     * @return array|false[]
     */
    private static function savePageSpeedScreenshot(string $base64Image): array
    {
        $imageContent = base64_decode($base64Image, true);

        if ($imageContent === false) {
            return self::failedResult();
        }

        try {
            self::ensureDirectory(public_path(self::SCREENSHOT_DIRECTORY));

            $filename = self::filename('jpg');
            $image = ImageResize::createFromString($imageContent);
            $image->crop(1200, 800, true, ImageResize::CROPTOP);

            if ($image->save(public_path(self::SCREENSHOT_DIRECTORY).'/'.$filename) === false) {
                return self::failedResult();
            }
        } catch (Throwable) {
            return self::failedResult();
        }

        return self::successfulResult($filename);
    }

    /**
     * Сохраняет миниатюру скриншота в storage-диск ссылок.
     *
     * @param string $imageContent
     * @param string $format
     * @return array|false[]
     */
    private static function saveMiniScreenshot(string $imageContent, string $format): array
    {
        try {
            Storage::disk('links')->makeDirectory(self::LINKS_STORAGE_DIRECTORY);

            $filename = self::filename($format);
            $path = self::LINKS_STORAGE_DIRECTORY.'/'.$filename;

            if (Storage::disk('links')->put($path, $imageContent) === false) {
                return self::failedResult();
            }
        } catch (Throwable) {
            return self::failedResult();
        }

        return self::successfulResult($filename);
    }

    /**
     * Создает директорию, если она еще не существует.
     */
    private static function ensureDirectory(string $path): void
    {
        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    /**
     * Генерирует безопасное случайное имя файла с расширением.
     */
    private static function filename(string $extension): string
    {
        $extension = preg_replace('/[^a-zA-Z0-9]/', '', $extension) ?: 'jpg';
        $name = bin2hex(random_bytes(16));

        return $name.'.'.strtolower($extension);
    }

    /**
     * Возвращает успешный результат сохранения файла.
     */
    private static function successfulResult(string $filename): array
    {
        return [
            'result' => true,
            'name' => $filename,
        ];
    }

    /**
     * Возвращает результат неуспешной операции с файлом.
     */
    private static function failedResult(): array
    {
        return ['result' => false];
    }
}
