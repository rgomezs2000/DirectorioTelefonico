<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Api\SwaggerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/ingresar', [AuthController::class, 'ingresar'])->name('ingresar');
Route::get('/auth_google/status', [AuthController::class, 'googleStatus'])->name('auth.google.status');
Route::post('/auth_google', [AuthController::class, 'authGoogle'])->name('auth.google');

Route::get('/api', [SwaggerController::class, 'ui'])->name('swagger.ui');
Route::get('/api/openapi.json', [SwaggerController::class, 'spec'])->name('swagger.spec');
