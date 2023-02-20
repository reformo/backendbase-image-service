<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline\Convert;

use Imagick;

class Avif implements Converter
{
    public function convert(string $sourceFile): string
    {
        if (PHP_VERSION_ID < 80100 || mime_content_type($sourceFile) === 'image/avif') {
            return $sourceFile;
        }
        $newSourceFile = str_contains($sourceFile, '.avif') ? $sourceFile : $sourceFile.'.avif';
        $image = new Imagick($sourceFile);
        $image->setImageFormat('AVIF');
        $image->setOption('heic:lossless', 'true');
        $image->writeImage($newSourceFile);
        if ($newSourceFile !== $sourceFile) {
            unlink($sourceFile);
        }
        return $newSourceFile;
    }
}