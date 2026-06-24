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
     * Fetches a full-size website screenshot through the PageSpeed API and saves it.
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
     * Fetches a screenshot thumbnail through an external service and saves it.
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
     * Returns URL contents using the given timeout.
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
     * Checks whether the string is a valid URL after adding a scheme.
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
     * Builds the PageSpeed API request URL for fetching a screenshot.
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
     * Builds the request URL for the screenshot thumbnail service.
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
     * Adds the default scheme to a URL when it is missing.
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
     * Executes a cURL request and returns the response body.
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
     * Extracts base64 screenshot data from the PageSpeed API response.
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
     * Decodes and saves a PageSpeed screenshot to the local directory.
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
     * Saves the screenshot thumbnail to the links storage disk.
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
     * Creates the directory if it does not exist yet.
     */
    private static function ensureDirectory(string $path): void
    {
        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    /**
     * Generates a safe random file name with an extension.
     */
    private static function filename(string $extension): string
    {
        $extension = preg_replace('/[^a-zA-Z0-9]/', '', $extension) ?: 'jpg';
        $name = bin2hex(random_bytes(16));

        return $name.'.'.strtolower($extension);
    }

    /**
     * Returns the result of a successful file save.
     */
    private static function successfulResult(string $filename): array
    {
        return [
            'result' => true,
            'name' => $filename,
        ];
    }

    /**
     * Returns the result of a failed file operation.
     */
    private static function failedResult(): array
    {
        return ['result' => false];
    }
}
