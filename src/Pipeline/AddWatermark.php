<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline;

use Backendbase\Utility\Pipeline\PipeInterface;

class AddWatermark implements PipeInterface
{
    public function __construct(private string $watermark) {}
    public function __invoke($payload)
    {
        return $payload;
    }
}