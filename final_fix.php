<?php
require 'vendor/autoload.php';

$loader = require 'vendor/autoload.php';
$loader->addClassMap([
    'Illuminate\\Config\\ConfigServiceProvider' => __DIR__.'/vendor/laravel/framework/src/Illuminate/Config/ConfigServiceProvider.php'
]);

$app = require 'bootstrap/app.php';
$app->register(new Illuminate\Config\ConfigServiceProvider($app));
echo "¡ConfigServiceProvider registrado correctamente!";
