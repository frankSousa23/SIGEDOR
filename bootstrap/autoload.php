<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
$files = require __DIR__.'/../vendor/composer/autoload_classmap.php';

foreach ($files as $class => $file) {
    if (!class_exists($class)) {
        require $file;
    }
}

$app = require_once __DIR__.'/../bootstrap/app.php';
