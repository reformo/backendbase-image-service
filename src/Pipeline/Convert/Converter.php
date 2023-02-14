<?php

declare(strict_types=1);

namespace Backendbase\ImageService\Pipeline\Convert;

interface Converter
{
    public function convert(string $sourceFile) : string;
}