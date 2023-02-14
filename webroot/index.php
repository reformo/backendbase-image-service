<?php

declare(strict_types=1);



use Laminas\Diactoros\ServerRequestFactory;
use Backendbase\ImageService\ImageService;
use Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';
chdir(__DIR__);

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$config = require 'config.php';

$imageService = ImageService::create($config['backendbase']['image-service'])
    ->process($request->getUri()->getPath());
if ($imageService === null) {
    http_response_code(404);
    header("HTTP/1.0 404 Not Found");
    die('<h1>HTTP/1.0 404 Not Found</h1>');
}
if ($imageService !== null) {
    $fileFullPath = $imageService['targetFile'];
    header('Content-Type: ' . mime_content_type($fileFullPath));
    echo file_get_contents($fileFullPath);
}