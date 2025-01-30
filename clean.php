<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Foundation\Console\Kernel')->call('config:clear');
echo "Cache de configuración limpiado!";
