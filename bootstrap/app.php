<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton('config', function ($app) {
    return new \Illuminate\Config\Repository([]);
});

$app->singleton('config.loader', function ($app) {
    return new \Illuminate\Config\FileLoader($app['files'], $app->configPath());
});

require_once __DIR__.'/../vendor/laravel/framework/src/Illuminate/Foundation/Application.php';
require __DIR__.'/../vendor/laravel/framework/src/Illuminate/Foundation/helpers.php';

return $app;
