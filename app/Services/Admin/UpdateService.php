<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;
use ZipArchive;

class UpdateService
{
    private const DEFAULT_VERSION = '4.0.1';

    /**
     * Returns the update step list for the step-by-step AJAX process.
     *
     * @return array<string, array{label: string, progress: int}>
     */
    public function steps(): array
    {
        return [
            'download_update' => [
                'label' => __('interface.admin.updates.downloading', ['file' => 'update.zip']),
                'progress' => 15,
            ],
            'download_public' => [
                'label' => __('interface.admin.updates.downloading', ['file' => 'public.zip']),
                'progress' => 30,
            ],
            'download_vendor' => [
                'label' => __('interface.admin.updates.downloading', ['file' => 'vendor.zip']),
                'progress' => 40,
            ],
            'extract_update' => [
                'label' => __('interface.admin.updates.extracting', ['file' => 'update.zip']),
                'progress' => 55,
            ],
            'extract_public' => [
                'label' => __('interface.admin.updates.extracting', ['file' => 'public.zip']),
                'progress' => 70,
            ],
            'extract_vendor' => [
                'label' => __('interface.admin.updates.extracting', ['file' => 'vendor.zip']),
                'progress' => 80,
            ],
            'migrate' => [
                'label' => __('interface.admin.updates.database'),
                'progress' => 92,
            ],
            'clear_cache' => [
                'label' => __('interface.admin.updates.cache'),
                'progress' => 100,
            ],
        ];
    }

    /**
     * Loads available version information from the license server.
     *
     * @return array<string, mixed>
     */
    public function check(?string $locale = null): array
    {
        $currentVersion = $this->currentVersion();
        $sourceUrl = $this->infoUrl($locale, $currentVersion);

        try {
            $response = Http::timeout(15)->acceptJson()->get($sourceUrl);

            if (! $response->successful()) {
                throw new RuntimeException('HTTP '.$response->status());
            }

            $payload = $response->json();

            if (! is_array($payload)) {
                throw new RuntimeException('Invalid update response.');
            }
        } catch (Throwable $exception) {
            return $this->emptyInfo($currentVersion, $sourceUrl, $exception->getMessage());
        }

        $latestVersion = (string) ($payload['version'] ?? '');
        $upgradeVersion = (string) ($payload['upgrade_version'] ?? $latestVersion);
        $downloadUrl = (string) ($payload['download'] ?? '');
        $updateUrl = $this->normalizeUpdateUrl((string) ($payload['update'] ?? ''));

        return [
            'success' => true,
            'current_version' => $currentVersion,
            'latest_version' => $latestVersion,
            'upgrade_version' => $upgradeVersion,
            'available' => $this->hasNewVersion($upgradeVersion, $currentVersion),
            'download_url' => $downloadUrl,
            'update_url' => $updateUrl,
            'created' => (string) ($payload['created'] ?? ''),
            'message' => (string) ($payload['message'] ?? ''),
            'source_url' => $sourceUrl,
            'error' => null,
        ];
    }

    /**
     * Runs one update step and returns the interface status.
     *
     * @return array{result: bool, status: string, progress: int}
     */
    public function runStep(string $step, ?string $locale = null): array
    {
        $steps = $this->steps();

        if (! isset($steps[$step])) {
            return $this->response(false, __('interface.admin.updates.failed', ['message' => 'Unknown update step.']), 0);
        }

        $info = $this->check($locale);

        if (! ($info['success'] ?? false)) {
            return $this->response(false, __('interface.admin.updates.cannot_connect'), 0);
        }

        if (! ($info['available'] ?? false)) {
            return $this->response(
                false,
                __('interface.admin.updates.no_update', ['version' => $info['current_version']]),
                0
            );
        }

        try {
            $status = match ($step) {
                'download_update' => $this->downloadArchive($info, 'update.zip'),
                'download_public' => $this->downloadArchive($info, 'public.zip'),
                'download_vendor' => $this->downloadArchive($info, 'vendor.zip'),
                'extract_update' => $this->extractArchive('update.zip'),
                'extract_public' => $this->extractArchive('public.zip'),
                'extract_vendor' => $this->extractArchive('vendor.zip'),
                'migrate' => $this->updateDatabase(),
                'clear_cache' => $this->clearCache((string) $info['upgrade_version']),
                default => throw new RuntimeException('Unknown update step.'),
            };
        } catch (Throwable $exception) {
            return $this->response(
                false,
                __('interface.admin.updates.failed', ['message' => $exception->getMessage()]),
                (int) $steps[$step]['progress']
            );
        }

        return $this->response(true, $status, (int) $steps[$step]['progress']);
    }

    /**
     * Builds the update information request URL.
     */
    public function infoUrl(?string $locale = null, ?string $version = null): string
    {
        return rtrim((string) config('update.endpoint'), '/').'/?'.http_build_query([
            'id' => (int) config('update.project_id'),
            'version' => $version ?: $this->currentVersion(),
            'lang' => $locale ?: app()->getLocale(),
        ]);
    }

    /**
     * Returns the current application version.
     */
    private function currentVersion(): string
    {
        $version = (string) config('update.version', '');

        return $this->isVersion($version) ? $version : self::DEFAULT_VERSION;
    }

    /**
     * Downloads the update archive to storage/app/update.
     *
     * @param array<string, mixed> $info
     */
    private function downloadArchive(array $info, string $fileName): string
    {
        $updateUrl = (string) ($info['update_url'] ?? '');

        if ($updateUrl === '') {
            throw new RuntimeException(__('interface.admin.updates.download_missing'));
        }

        File::ensureDirectoryExists($this->updateDirectory());

        $target = $this->archivePath($fileName);
        $response = Http::timeout(180)->sink($target)->get($updateUrl.'/'.$fileName);

        if (! $response->successful()) {
            File::delete($target);

            throw new RuntimeException(__('interface.admin.updates.download_missing'));
        }

        return __('interface.admin.updates.download_completed', ['file' => $fileName]);
    }

    /**
     * Extracts the downloaded archive into the project root.
     */
    private function extractArchive(string $fileName): string
    {
        $archive = $this->archivePath($fileName);

        if (! File::exists($archive)) {
            throw new RuntimeException(__('interface.admin.updates.archive_error', ['file' => $fileName]));
        }

        if (! is_writable(base_path())) {
            throw new RuntimeException(__('interface.admin.updates.write_error'));
        }

        $zip = new ZipArchive();
        $result = $zip->open($archive);

        if ($result !== true) {
            throw new RuntimeException(__('interface.admin.updates.archive_error', ['file' => $fileName]));
        }

        try {
            if ($this->archiveHasUnsafeEntries($zip)) {
                throw new RuntimeException(__('interface.admin.updates.unsafe_archive', ['file' => $fileName]));
            }

            $zip->extractTo(base_path());
        } finally {
            $zip->close();
        }

        return __('interface.admin.updates.extract_completed', ['file' => $fileName]);
    }

    /**
     * Runs migrations after updating files.
     */
    private function updateDatabase(): string
    {
        $exitCode = Artisan::call('migrate', ['--force' => true]);

        if ($exitCode !== 0) {
            throw new RuntimeException(trim(Artisan::output()) ?: __('interface.admin.updates.failed', ['message' => 'Migration failed.']));
        }

        return __('interface.admin.updates.database_completed');
    }

    /**
     * Updates VERSION in .env and clears Laravel caches.
     */
    private function clearCache(string $version): string
    {
        $this->setEnvironmentValue('VERSION', $version);
        Artisan::call('optimize:clear');

        return __('interface.admin.updates.completed', ['version' => $version]);
    }

    /**
     * Checks that the archive has no absolute paths or upward traversal.
     */
    private function archiveHasUnsafeEntries(ZipArchive $zip): bool
    {
        for ($index = 0; $index < $zip->numFiles; $index++) {
            $name = str_replace('\\', '/', (string) $zip->getNameIndex($index));

            if (
                $name === ''
                || str_contains($name, "\0")
                || str_starts_with($name, '/')
                || str_starts_with($name, '../')
                || str_contains($name, '/../')
                || preg_match('/^[A-Za-z]:\\//', $name) === 1
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Writes an environment variable value to .env.
     */
    private function setEnvironmentValue(string $key, string $value): void
    {
        $path = base_path('.env');

        if (! File::exists($path) || ! is_writable($path)) {
            throw new RuntimeException(__('interface.admin.updates.write_error'));
        }

        $line = $key.'='.$this->escapeEnvironmentValue($value);
        $contents = File::get($path);

        if (preg_match('/^'.preg_quote($key, '/').'=.*/m', $contents) === 1) {
            $contents = (string) preg_replace('/^'.preg_quote($key, '/').'=.*/m', $line, $contents);
        } else {
            $contents = rtrim($contents).PHP_EOL.$line.PHP_EOL;
        }

        File::put($path, $contents);
    }

    /**
     * Prepares a value for safe writing to .env.
     */
    private function escapeEnvironmentValue(string $value): string
    {
        if (preg_match('/\s|#|"|\'/', $value) !== 1) {
            return $value;
        }

        return '"'.str_replace('"', '\\"', $value).'"';
    }

    /**
     * Returns the path to the temporary update archive directory.
     */
    private function updateDirectory(): string
    {
        return storage_path('app/update');
    }

    /**
     * Returns the full path to the downloaded archive.
     */
    private function archivePath(string $fileName): string
    {
        return $this->updateDirectory().DIRECTORY_SEPARATOR.$fileName;
    }

    /**
     * Normalizes the update archive directory link.
     */
    private function normalizeUpdateUrl(string $url): string
    {
        if ($url === '') {
            return '';
        }

        $path = (string) parse_url($url, PHP_URL_PATH);

        if (pathinfo($path, PATHINFO_EXTENSION) === 'zip') {
            $url = dirname($url);
        }

        return rtrim($url, '/');
    }

    /**
     * Checks that the version looks like semver x.y.z.
     */
    private function isVersion(string $version): bool
    {
        return preg_match('/^\d+\.\d+\.\d+$/', $version) === 1;
    }

    /**
     * Determines whether an update version is available.
     */
    private function hasNewVersion(string $upgradeVersion, string $currentVersion): bool
    {
        return $this->isVersion($upgradeVersion)
            && $this->isVersion($currentVersion)
            && version_compare($upgradeVersion, $currentVersion, '>');
    }

    /**
     * Returns an empty update check result when the update server fails.
     *
     * @return array<string, mixed>
     */
    private function emptyInfo(string $currentVersion, string $sourceUrl, string $error): array
    {
        return [
            'success' => false,
            'current_version' => $currentVersion,
            'latest_version' => '',
            'upgrade_version' => '',
            'available' => false,
            'download_url' => '',
            'update_url' => '',
            'created' => '',
            'message' => '',
            'source_url' => $sourceUrl,
            'error' => $error,
        ];
    }

    /**
     * Builds a unified JSON response for an update step.
     *
     * @return array{result: bool, status: string, progress: int}
     */
    private function response(bool $result, string $status, int $progress): array
    {
        return [
            'result' => $result,
            'status' => $status,
            'progress' => $progress,
        ];
    }
}
