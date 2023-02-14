<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline\Convert;

class Avif implements Converter
{

    use ImageCreateUtility;

    public function convert(string $sourceFile): string
    {

        if (PHP_VERSION_ID < 81000 || mime_content_type($sourceFile) === 'image/avif') {
            return $sourceFile;
        }
        $image = $this->createImageFromFilename($sourceFile);
        imageavif($image, $sourceFile.'avif', 0, -1);
        imagedestroy($image);
        return $sourceFile.'avif';
    }
}