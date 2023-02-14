<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline\Convert;

class Png implements Converter
{

    use ImageCreateUtility;

    public function convert(string $sourceFile): string
    {
        if (mime_content_type($sourceFile) === 'image/png') {
            return $sourceFile;
        }
        $image = $this->createImageFromFilename($sourceFile);
        imagepng($image, $sourceFile, 0, -1);
        imagedestroy($image);
        return $sourceFile;
    }
}