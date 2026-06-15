<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageOptimizer
{
    /**
     * @return array{path:string,webp_path:?string,thumbnail_path:?string,width:int,height:int,mime_type:string,size:int,original_name:string}
     */
    public function store(
        UploadedFile $file,
        string $directory,
        string $disk = 'public',
        int $maxWidth = 1800,
        int $thumbnailWidth = 520,
    ): array {
        $directory = trim($directory, '/');
        $realPath = $file->getRealPath();

        if (! $realPath || ! is_file($realPath)) {
            throw new RuntimeException('The uploaded image could not be read.');
        }

        $info = getimagesize($realPath);

        if (! is_array($info)) {
            throw new RuntimeException('The uploaded file is not a valid image.');
        }

        [$width, $height] = $info;
        $mime = (string) ($info['mime'] ?? $file->getMimeType() ?? '');
        $extension = $this->extensionForMime($mime);
        $baseName = (string) Str::uuid();
        $path = "{$directory}/{$baseName}.{$extension}";
        $webpPath = "{$directory}/{$baseName}.webp";
        $thumbnailPath = "{$directory}/{$baseName}-thumb.webp";

        Storage::disk($disk)->put($path, file_get_contents($realPath));

        $image = $this->loadImage($realPath);
        $optimized = $this->resize($image, (int) $width, (int) $height, $maxWidth);
        $thumbnail = $this->resize($image, (int) $width, (int) $height, $thumbnailWidth);

        Storage::disk($disk)->put($webpPath, $this->toWebp($optimized));
        Storage::disk($disk)->put($thumbnailPath, $this->toWebp($thumbnail));

        imagedestroy($image);
        imagedestroy($optimized);
        imagedestroy($thumbnail);

        return [
            'path' => $path,
            'webp_path' => $webpPath,
            'thumbnail_path' => $thumbnailPath,
            'width' => (int) $width,
            'height' => (int) $height,
            'mime_type' => $mime,
            'size' => (int) $file->getSize(),
            'original_name' => $this->safeOriginalName($file),
        ];
    }

    public function deleteStoredImages(array $paths, string $disk = 'public'): void
    {
        $paths = array_values(array_unique(array_filter($paths)));

        if ($paths !== []) {
            Storage::disk($disk)->delete($paths);
        }
    }

    private function extensionForMime(string $mime): string
    {
        return match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => throw new RuntimeException('Only JPG, PNG, and WEBP images are supported.'),
        };
    }

    private function loadImage(string $path): \GdImage
    {
        $image = imagecreatefromstring(file_get_contents($path));

        if (! $image instanceof \GdImage) {
            throw new RuntimeException('The uploaded image could not be processed.');
        }

        return $image;
    }

    private function resize(\GdImage $source, int $width, int $height, int $maxWidth): \GdImage
    {
        $targetWidth = min($width, $maxWidth);
        $targetHeight = (int) max(1, round($height * ($targetWidth / max(1, $width))));

        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);

        if (! $canvas instanceof \GdImage) {
            throw new RuntimeException('The image canvas could not be created.');
        }

        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
        imagefilledrectangle($canvas, 0, 0, $targetWidth, $targetHeight, $transparent);

        imagecopyresampled(
            $canvas,
            $source,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $width,
            $height,
        );

        return $canvas;
    }

    private function toWebp(\GdImage $image): string
    {
        ob_start();
        imagewebp($image, null, 82);
        $contents = ob_get_clean();

        if (! is_string($contents) || $contents === '') {
            throw new RuntimeException('The optimized image could not be generated.');
        }

        return $contents;
    }

    private function safeOriginalName(UploadedFile $file): string
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $safeName = Str::of($name)->ascii()->slug('-')->limit(90, '');

        return trim($safeName.'.'.$extension, '.');
    }
}
