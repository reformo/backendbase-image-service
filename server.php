<?php

if (PHP_SAPI !== 'cli-server') {
    die('this is only for the php development server');
}
$webroot = 'webroot';

$fileFullPath = getcwd() . '/' .  $webroot . $_SERVER['SCRIPT_NAME'];
if (is_file($fileFullPath)) {
    header('Content-Type: '. mime_content_type($fileFullPath));
    die(file_get_contents($fileFullPath));
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
// require the entry point
require $webroot . '/index.php';
