<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline\Convert;

class Original implements Converter
{

    public function convert(string $sourceFile) : string
    {
        return $sourceFile;
    }
}