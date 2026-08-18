<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/admin');
    }
    return view('welcome');
})->name('home');
