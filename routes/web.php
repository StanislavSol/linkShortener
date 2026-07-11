<?php

use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/{code}', [RedirectController::class, 'redirect'])
    ->where('code', '[A-Za-z0-9]+');
