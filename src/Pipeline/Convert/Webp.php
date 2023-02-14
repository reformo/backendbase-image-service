<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline\Convert;

use WebPConvert\WebPConvert;
use WebPConvert\Convert\Exceptions\ConversionFailedException;

class Webp implements Converter
{

    use ImageCreateUtility;

    public function convert(string $sourceFile): string
    {
        if (mime_content_type($sourceFile) === 'image/webp') {
            return $sourceFile;
        }
        $options = [
            'png' => [
                'encoding' => 'auto',
                'near-lossless' => 60,
                'quality' => 90,
            ],
            'jpeg' => [
                'encoding' => 'auto',
                'quality' => 'auto',
                'max-quality' => 85,
                'default-quality' => 75,
            ]
        ];
        try {
            var_dump(str_contains($sourceFile, '.wepb'));exit();
            $newSourceFile = str_contains($sourceFile, '.wepb') ? $sourceFile : $sourceFile.'.webp';
            WebPConvert::convert($sourceFile, $newSourceFile, $options);
            unlink($sourceFile);
            return $$newSourceFile;
        } catch (ConversionFailedException $exception) {
        }
        return $sourceFile;
    }
}