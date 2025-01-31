<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
echo get_class($app['config']); // Debe mostrar: Illuminate\Config\Repository
