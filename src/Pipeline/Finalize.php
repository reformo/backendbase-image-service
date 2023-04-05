<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline;

use Backendbase\Utility\Pipeline\PipeInterface;

class Finalize implements PipeInterface
{

    public function __invoke($payload)
    {
        $dirname = dirname($payload['targetFile']);
        if (!is_dir($dirname)) {
            if (!mkdir($dirname, 0755, true) && !is_dir($dirname)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dirname));
            }
        }
        copy($payload['tmpFile'], $payload['targetFile']);
        unlink($payload['tmpFile']);
        unlink($payload['pidFile']);
        return $payload;
    }
}