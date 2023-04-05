<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline;

use Backendbase\Utility\Pipeline\PipeInterface;

class CreateTmpFile implements PipeInterface
{

    public function __invoke($payload)
    {
        file_put_contents($payload['pidFile'], $payload['targetFile']);
        $payload['tmpFile'] = $payload['tmpDir'] . '/'. basename($payload['sourceFileName']);
        copy($payload['sourceFile'], $payload['tmpFile']);
        return $payload;
    }
}