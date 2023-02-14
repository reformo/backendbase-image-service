<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline\Convert;

use Backendbase\Utility\Pipeline\PipeInterface;

class Convert implements PipeInterface
{

    public static array $validFormats = [
        'jpg' => Jpeg::class,
        'jpeg' => Jpeg::class,
        'png' => Png::class,
        'webp' => Webp::class,
        'avif' => Avif::class,
    ];

    public static array $supportedFormats = [
        'jpg' => 'IMG_JPG',
        'jpeg' => 'IMG_JPG',
        'png' => 'IMG_PNG',
        'webp' => 'IMG_WEBP',
        'avif' => 'IMG_AVIF',
    ];

    private function __construct(private Converter $converter) {

    }

    public static function create(string $fqcnOfConvertStrategy) : self
    {
        return new self(new $fqcnOfConvertStrategy());
    }

    public function __invoke($payload)
    {
        $payload['tmpFile'] = $this->converter->convert($payload['tmpFile']);
        return $payload;
    }

    public static function getConverterType(string $format) : string
    {
        $converter = Original::class;
        if (array_key_exists($format, self::$supportedFormats)
            && defined(self::$supportedFormats[$format])
            && imagetypes()
            && constant(self::$supportedFormats[$format])
        ) {
            $converter = self::$validFormats[$format];
        }
        return $converter;
    }


}