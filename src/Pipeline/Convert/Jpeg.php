<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline\Convert;

class Jpeg implements Converter
{

    use ImageCreateUtility;

    public function convert(string $sourceFile): string
    {
        if (mime_content_type($sourceFile) === 'image/jpeg') {
            return $sourceFile;
        }
        $image = $this->createImageFromFilename($sourceFile);
        imagejpeg($image, $sourceFile, 100);
        imagedestroy($image);
        return $sourceFile;
    }
}