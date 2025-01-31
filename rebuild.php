<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

// Registrar proveedores manualmente
$app->register(new Illuminate\Config\ConfigServiceProvider($app));
$app->register(new Illuminate\Foundation\Providers\FoundationServiceProvider($app));

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
echo "¡Sistema reconstruido exitosamente!";
