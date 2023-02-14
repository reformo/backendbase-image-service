<?php

declare(strict_types=1);


return [
  'backendbase' => [
      'image-service' => [
          'url-prefix' => $_ENV['BACKENDBASE_IMAGE_SERVICE_URL_PREFIX'] ?? 'is',
          'source-dir' => $_ENV['BACKENDBASE_IMAGE_SERVICE_URL_SOURCE_DIR'] ?? '/',
          'tmp-dir' => $_ENV['BACKENDBASE_IMAGE_SERVICE_URL_TMP_DIR'] ?? '/tmp',
          'resize-strategy' =>  $_ENV['BACKENDBASE_IMAGE_SERVICE_URL_RESIZE_STRATEGY'] ?? 'r',
          'watermark' =>  $_ENV['BACKENDBASE_IMAGE_SERVICE_WATERMARK'] ?? '',
          'sizes' => [
              'is-exact-enabled' => (bool) ($_ENV['BACKENDBASE_IMAGE_SERVICE_SIZE_ENABLE_EXACT'] ?? 0),
              'default' => $_ENV['BACKENDBASE_IMAGE_SERVICE_SIZE_DEFAULT'],
              'bit' => $_ENV['BACKENDBASE_IMAGE_SERVICE_SIZE_BIT'] ?? 100,
              'thumb' => $_ENV['BACKENDBASE_IMAGE_SERVICE_SIZE_THUMB'] ?? 200,
              'small' => $_ENV['BACKENDBASE_IMAGE_SERVICE_SIZE_SMALL'] ?? 320,
              'medium' => $_ENV['BACKENDBASE_IMAGE_SERVICE_SIZE_MEDIUM'] ?? 640,
              'large' => $_ENV['BACKENDBASE_IMAGE_SERVICE_SIZE_LARGE'] ?? 1440,
              'original' => $_ENV['BACKENDBASE_IMAGE_SERVICE_SIZE_ORIGINAL'] ?? 1440,
          ]
      ]
  ]
];