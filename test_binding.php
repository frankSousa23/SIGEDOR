<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Foundation\MaintenanceMode');
echo "¡Binding funcionando correctamente!";
