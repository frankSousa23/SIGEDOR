<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->register(Illuminate\Foundation\Providers\FoundationServiceProvider::class); // ← Registrar manualmente
$maintenance = $app->make('Illuminate\Contracts\Foundation\MaintenanceMode');
echo "¡Binding funcionando! Clase: " . get_class($maintenance);
