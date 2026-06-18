<?php

namespace App\Services\Admin;

use Gumlet\ImageResize;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class CatalogImageService
{
    private const DIRECTORY = '/uploads/catalog/';

    public function store(UploadedFile $file): string
    {
        $destinationPath = public_path(self::DIRECTORY);
        File::ensureDirectoryExists($destinationPath);

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $image = new ImageResize($file->getRealPath());
        $image->resizeToBestFit(150, 150);
        $image->save($destinationPath . '/' . $filename);

        return $filename;
    }

    public function delete(?string $filename): void
    {
        if ($filename === null || $filename === '' || $filename === 'NULL') {
            return;
        }

        $path = public_path(self::DIRECTORY . $filename);

        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
