<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline;

use Backendbase\Utility\Pipeline\PipeInterface;
use Gumlet\ImageResize;

class Resize implements PipeInterface
{
    public static array $allowedResizeStrategies = [
        'r' => ['function' => 'resizeToWidth', 'dimensionKey' => 'width'],
        'rh' => ['function' => 'resizeToHeight', 'dimensionKey' => 'height'],
        'ct' => ['type' => 'crop', 'aspectRatio' => 'portrait', 'position' => 'top'],
        'cm' => ['type' => 'crop', 'aspectRatio' => 'portrait', 'position' => 'middle'],
        'cb' => ['type' => 'crop', 'aspectRatio' => 'portrait', 'position' => 'bottom'],
        'cl' => ['type' => 'crop', 'resizeToWidth' => 'landscape', 'position' => 'left'],
        'cc' => ['type' => 'crop', 'resizeToWidth' => 'landscape', 'position' => 'center'],
        'cr' => ['type' => 'crop', 'resizeToWidth' => 'landscape', 'position' => 'right'],
        'sc' => ['type' => 'crop', 'aspectRatio' => 'square'],
    ];
    public function __invoke($payload)
    {
        $image = new ImageResize($payload['tmpFile']);
        $resizeStrategy = $payload['modifierConfig']['resizeStrategy'];
        if (array_key_exists('function', $resizeStrategy)) {
            $size = $payload['modifierConfig']['size'][$resizeStrategy['dimensionKey']];
            $image->{$resizeStrategy['function']}($size);
            $image->save($payload['tmpFile'], null, null);
        }
        return $payload;
    }

    public static function getResizeStrategyType(string $resizeStrategy) : array
    {
        return self::$allowedResizeStrategies[$resizeStrategy] ?? self::$allowedResizeStrategies['r'];
    }

    public static function getImageSize(string $modifier, $allowedImageSizes) : array
    {
        if (array_key_exists('is-exact-enabled', $allowedImageSizes)
            && $allowedImageSizes['is-exact-enabled']
            && str_starts_with($modifier, 'exact')
        ) {
            [, $size] = explode('-', $modifier);
            [$width, $height] = explode('x', $size);
            return [
                'width' => $width,
                'height' => $height
            ];
        }
        if (array_key_exists($modifier, $allowedImageSizes)) {
            return [
                'width' => $allowedImageSizes[$modifier],
                'height' => $allowedImageSizes[$modifier]
            ];
        }

        return [
            'width' => $allowedImageSizes['default'],
            'height' => $allowedImageSizes['default']
        ];
    }

}