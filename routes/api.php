<?php

use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\TeacherApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/ping', function () {
    return response()->json([
        'status' => 'pong',
        'timestamp' => now()->toIso8601String(),
        'version' => '1.0.0',
    ]);
})->middleware('throttle:60,1');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum', 'throttle:60,1']);

Route::prefix('v1')->middleware('throttle:60,1')->group(function () {
    Route::get('/teachers', [TeacherApiController::class, 'index']);
    Route::get('/reports', [ReportApiController::class, 'index']);
});
