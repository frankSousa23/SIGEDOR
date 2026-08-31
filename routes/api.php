<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\TeacherApiController;

Route::prefix('v1')->group(function () {
    Route::get('/teachers', [TeacherApiController::class, 'index']);
    Route::get('/reports', [ReportApiController::class, 'index']);
});
