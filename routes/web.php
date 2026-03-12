<?php

use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Api\SwaggerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'login'])->name('login');

Route::get('/api', [SwaggerController::class, 'ui'])->name('swagger.ui');
Route::get('/api/openapi.json', [SwaggerController::class, 'spec'])->name('swagger.spec');
