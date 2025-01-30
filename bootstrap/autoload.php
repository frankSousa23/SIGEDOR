<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
$files = require __DIR__.'/../vendor/composer/autoload_files.php';

foreach ($files as $file) {
    require $file;
}

$app = require_once __DIR__.'/../bootstrap/app.php';
