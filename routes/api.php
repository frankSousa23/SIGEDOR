<?php

use Illuminate\Support\Facades\Route;

/* Ruta de salud para verificación */
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
