<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function storeWithThumbnail(UploadedFile $file): array
    {
        $disk = 'public';
        $datePath = date('Y/m');
        $baseDir = "uploads/{$datePath}";

        // Store original
        $originalPath = $file->store($baseDir, $disk);

        // Determine thumbnail size from settings
        [$thumbW, $thumbH] = $this->getThumbnailSize();

        $thumbPath = $this->generateThumbnail($disk, $originalPath, $thumbW, $thumbH);

        $mime = $file->getMimeType();

        $media = Media::create([
            'path' => $originalPath,
            'mime_type' => $mime,
        ]);

        return [
            'media' => $media,
            'thumbnail_path' => $thumbPath,
        ];
    }

    private function getThumbnailSize(): array
    {
        $setting = Setting::where('key_name', 'thumbnail_size')->first();
        $value = null;
        if ($setting) {
            $value = optional($setting->values()->where('locale', null)->latest()->first())->value_text;
        }

        if (is_string($value) && preg_match('/^(\d+)\s*[xX]\s*(\d+)$/', $value, $m)) {
            return [max(1, (int)$m[1]), max(1, (int)$m[2])];
        }

        return [300, 300];
    }

    private function generateThumbnail(string $disk, string $originalPath, int $width, int $height): string
    {
        $fullOriginal = Storage::disk($disk)->path($originalPath);

        $pathInfo = pathinfo($originalPath);
        $thumbFilename = $pathInfo['filename'] . "_thumb{$width}x{$height}." . $pathInfo['extension'];
        $thumbRelativePath = $pathInfo['dirname'] . '/' . $thumbFilename;
        $fullThumb = Storage::disk($disk)->path($thumbRelativePath);

        $this->resizeImageGd($fullOriginal, $fullThumb, $width, $height);

        return $thumbRelativePath;
    }

    private function resizeImageGd(string $srcPath, string $dstPath, int $targetW, int $targetH): void
    {
        $info = getimagesize($srcPath);
        if ($info === false) {
            copy($srcPath, $dstPath);
            return;
        }
        [$srcW, $srcH] = $info;
        $mime = $info['mime'] ?? '';

        switch ($mime) {
            case 'image/jpeg':
                $srcImg = imagecreatefromjpeg($srcPath);
                break;
            case 'image/png':
                $srcImg = imagecreatefrompng($srcPath);
                break;
            case 'image/gif':
                $srcImg = imagecreatefromgif($srcPath);
                break;
            default:
                copy($srcPath, $dstPath);
                return;
        }

        $dstImg = imagecreatetruecolor($targetW, $targetH);

        // Preserve transparency for PNG and GIF
        if (in_array($mime, ['image/png', 'image/gif'])) {
            imagecolortransparent($dstImg, imagecolorallocatealpha($dstImg, 0, 0, 0, 127));
            imagealphablending($dstImg, false);
            imagesavealpha($dstImg, true);
        }

        // Fit and crop center
        $srcRatio = $srcW / $srcH;
        $dstRatio = $targetW / $targetH;
        if ($srcRatio > $dstRatio) {
            $newH = $targetH;
            $newW = (int) round($targetH * $srcRatio);
        } else {
            $newW = $targetW;
            $newH = (int) round($targetW / $srcRatio);
        }

        $temp = imagecreatetruecolor($newW, $newH);
        if (in_array($mime, ['image/png', 'image/gif'])) {
            imagecolortransparent($temp, imagecolorallocatealpha($temp, 0, 0, 0, 127));
            imagealphablending($temp, false);
            imagesavealpha($temp, true);
        }
        imagecopyresampled($temp, $srcImg, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);

        $x = (int) floor(($newW - $targetW) / 2);
        $y = (int) floor(($newH - $targetH) / 2);
        imagecopy($dstImg, $temp, 0, 0, $x, $y, $targetW, $targetH);

        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($dstImg, $dstPath, 85);
                break;
            case 'image/png':
                imagepng($dstImg, $dstPath, 6);
                break;
            case 'image/gif':
                imagegif($dstImg, $dstPath);
                break;
        }

        imagedestroy($srcImg);
        imagedestroy($temp);
        imagedestroy($dstImg);
    }
}


