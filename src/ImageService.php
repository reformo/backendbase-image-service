<?php

declare(strict_types=1);

namespace Backendbase\ImageService;

use Backendbase\ImageService\Pipeline\Convert\Convert;
use Backendbase\ImageService\Pipeline\CreateTmpFile;
use Backendbase\ImageService\Pipeline\Finalize;
use Backendbase\ImageService\Pipeline\Optimize;
use Backendbase\ImageService\Pipeline\Resize;
use Backendbase\ImageService\Pipeline\AddWatermark;
use Backendbase\Utility\Pipeline\Pipeline;
use Backendbase\Utility\Pipeline\PipelineInterface;

class ImageService
{


    public function __construct(
        private string $urlPrefix,
        private string $sourceDir,
        private array $allowedImageSizes,
        private string $resizeStrategy,
        private string $watermark,
        private PipelineInterface $pipeline
    ){}

    public static function create(array $config): ImageService
    {
        return new self(
            $config['url-prefix'],
            $config['source-dir'],
            $config['sizes'],
            $config['resize-strategy'],
            empty($config['watermark']) ? '' : $config['watermark'],
            Pipeline::new()
        );
    }
    public function process(string $requestUrl)
    {
        $url = urldecode($requestUrl);
        if (strpos($url, $this->urlPrefix) !== 1) {
            return null;
        }
        $relativeUrl = '/' . trim(str_replace($this->urlPrefix,  '', $url), '/');
        $rootDir = getcwd();
        ['sourcePath' => $sourcePath, 'modifierConfig' => $modifierConfig] = $this->parseUrl($rootDir, $relativeUrl);
        return $this->run($rootDir, $relativeUrl, $sourcePath, $modifierConfig);
    }

    private function run(string $rootDir, string $relativeUrl, string $sourcePath, array  $modifierConfig)
    {
        if (!file_exists($rootDir .  $this->sourceDir . $sourcePath)) {
            return null;
        }
        $mimeContentType = mime_content_type($rootDir .  $this->sourceDir . $sourcePath);
        if (!str_starts_with($mimeContentType, 'image')) {
            return null;
        }
        $payload = [
            'rootDir' => $rootDir,
            'tmpDir' => sys_get_temp_dir(),
            'targetFile' => $rootDir . $this->sourceDir. '/' . $this->urlPrefix . $relativeUrl,
            'sourceFile' => $rootDir .  $this->sourceDir .$sourcePath,
            'sourceFileName' => basename($rootDir .  $this->sourceDir .$sourcePath),
            'sourceContentType' => $mimeContentType,
            'modifierConfig' => $modifierConfig,
        ];
        return $this->pipeline
            ->pipe(new CreateTmpFile())
            ->pipe(Convert::create($modifierConfig['format']))
            ->pipe(new Resize())
            ->pipe(new Optimize())
            ->pipe(new AddWatermark($this->watermark))
            ->pipe(new Finalize())
            ->process($payload);
    }

    private function parseUrl(string $rootDir, string $url) : array
    {
        $trimmedPath = trim($url, '/');

        [$modifiers,] = explode('/', $trimmedPath);
        if (is_dir($rootDir . $this->sourceDir . '/' .$modifiers)) {
            $modifiers = '';
        }

        $sourcePath = str_replace($modifiers, '', $trimmedPath);
        if (str_starts_with(trim($this->sourceDir, '/'), $modifiers)) {
            $sourcePath = '/' . $modifiers . $sourcePath;
            $modifiers = 's[default]:r[default]';
        }

        $filename = basename($sourcePath);
        $filenameParts = explode('.', $filename);
        if (count($filenameParts) > 2) {
            [$modifiers, $sourcePath] = $this->getFormatModifier($rootDir, $sourcePath, $modifiers, $filenameParts);
        }
        return ['modifierConfig' => $this->parseModifiers($modifiers), 'sourcePath' => $sourcePath];
    }

    private function getFormatModifier(string $rootDir, string $sourcePath, string $modifiers, array $filenameParts) : array
    {
        $targetExtension = array_pop($filenameParts);
        if (!file_exists($rootDir . $this->sourceDir . str_replace('.'. $targetExtension, '', $sourcePath))) {
            return [$modifiers, $sourcePath];
        }
        if (array_key_exists($targetExtension, Convert::$validFormats)) {
            $modifiers .= ":f[{$targetExtension}]";
        }
        return [$modifiers, str_replace('.'. $targetExtension, '', $sourcePath)];
    }

    private function parseModifiers(string $modifiers) : array
    {
        $modifierConfig = [
            'format' => '\\'.Convert::getConverterType('original'),
            'size' => [
                'width' => $this->allowedImageSizes['default'],
                'height' => $this->allowedImageSizes['default']
            ],
            'resizeStrategy' => Resize::getResizeStrategyType($this->resizeStrategy),
        ];
        foreach (explode(':', $modifiers) as $modifier) {
            if (str_starts_with($modifier, 'f[')) {
                $modifierConfig['format'] = '\\'.$this->parseFormatModifier($modifier);
            }
            if (str_starts_with($modifier, 's[')) {
                $modifierConfig['size'] = $this->parseSizeModifier($modifier);
            }
            if (str_starts_with($modifier, 'r[')) {
                $modifierConfig['resizeStrategy'] = $this->parseResizeStrategyModifier($modifier);
            }
        }
        return $modifierConfig;
    }

    private function parseFormatModifier(string $modifier) : string
    {
        $modifierString = str_replace(['f[', ']'], '', $modifier);
        return Convert::getConverterType($modifierString);
    }

    private function parseSizeModifier(string $modifier) : array
    {
        $modifierString = str_replace(['s[', ']'], '', $modifier);
        return Resize::getImageSize($modifierString, $this->allowedImageSizes);
    }

    private function parseResizeStrategyModifier(string $modifier) : array
    {
        $modifierString = str_replace(['r[', ']'], '', $modifier);
        return Resize::getResizeStrategyType($modifierString);

    }
}