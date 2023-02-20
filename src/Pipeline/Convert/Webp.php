<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline\Convert;

use Imagick;

class Webp implements Converter
{
    public function convert(string $sourceFile): string
    {
        if (mime_content_type($sourceFile) === 'image/webp') {
            return $sourceFile;
        }
        try {
            $newSourceFile = str_contains($sourceFile, '.wepb') ? $sourceFile : $sourceFile.'.webp';
            $image = new Imagick($sourceFile);
            $image->setImageFormat('WEBP');
            $image->setOption('webp:image-hint', 'photo');
            $image->setOption('webp:method', '4');
            $image->setOption('webp:alpha-quality', '100');
            $image->setOption('webp:use-sharp-yuv', 'true');
            $image->setOption('webp:auto-filter', 'true');
            $imageFileContent = $image->getImageBlob();
            file_put_contents($newSourceFile, $imageFileContent);
            if ($newSourceFile !== $sourceFile) {
                unlink($sourceFile);
            }
            return $newSourceFile;
        } catch (\Exception $exception) {
        }
        return $sourceFile;
    }
}