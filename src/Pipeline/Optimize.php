<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline;

use Backendbase\Utility\Pipeline\PipeInterface;
use ImageOptimizer\OptimizerFactory;

class Optimize implements PipeInterface
{

    private static array $optimizableTypes = [
        'image/jpeg' => 'jpeg',
        'image/png' => 'png',
    ];

    public function __invoke($payload)
    {
        if (!array_key_exists(mime_content_type($payload['tmpFile']), self::$optimizableTypes)) {
            return $payload;
        }
        $optimizerConfig = [
            'ignore_errors' => false,
            'output_filepath_pattern' => $payload['tmpFile'],
        ];
        $optimizer = (new OptimizerFactory($optimizerConfig))
            ->get(self::$optimizableTypes[mime_content_type($payload['tmpFile'])]);
        $optimizer->optimize($payload['tmpFile']);
        return $payload;
    }
}