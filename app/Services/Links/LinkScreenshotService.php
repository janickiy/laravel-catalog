<?php

namespace App\Services\Links;

use Gumlet\ImageResize;
use Gumlet\ImageResizeException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class LinkScreenshotService
{
    public static function rules(): array
    {
        return ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:max_width=6000,max_height=6000'];
    }

    public function store(UploadedFile $file): string
    {
        try {
            $image = new ImageResize($file->getRealPath());
            $image->resizeToBestFit(1600, 1200);
            $contents = $image->getImageAsString(IMAGETYPE_JPEG, 85);
        } catch (ImageResizeException|\ValueError|\TypeError $exception) {
            throw ValidationException::withMessages(['screenshot' => __('link_validation.screenshot_image')]);
        }

        $filename = 'upload-'.Str::uuid().'.jpg';
        if (! Storage::disk('links')->put('url/'.$filename, $contents)) {
            throw new RuntimeException('Unable to store the website screenshot.');
        }

        return $filename;
    }

    public function delete(?string $filename): void
    {
        // Demo and legacy screenshots can be shared by several records.
        if ($filename && preg_match('/^upload-[a-f0-9-]{36}\.jpg$/D', $filename)) {
            Storage::disk('links')->delete('url/'.$filename);
        }
    }
}
