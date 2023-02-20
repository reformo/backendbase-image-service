<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline\Convert;

use Backendbase\ImageService\Pipeline\Convert\Exception\UnsupportedImageMimeType;
use GdImage;

trait ImageCreateUtility
{

    private static array $mimeFunctionMappings = [
      'image/jpeg' => 'imagecreatefromjpeg',
      'image/png' => 'imagecreatefrompng',
      'image/webp' => 'imagecreatefromwebp',
      'image/avif' => 'imagecreatefromavif',
    ];

    private function createImageFromFilename(string $filename) : GdImage
    {
        $mimeType = mime_content_type($filename);
        $createImageFunction = self::$mimeFunctionMappings[$mimeType] ?? null;
        if ($createImageFunction === null) {
            throw new UnsupportedImageMimeType(
                sprintf(
                    'Unsupported image mime type: %s. Supported mime types are: %s',
                    $mimeType,
                    implode(', ', array_keys(self::$mimeFunctionMappings))
                )
            );
        }
        return $createImageFunction($filename);
    }
}