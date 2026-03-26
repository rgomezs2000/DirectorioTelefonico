<?php

use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\LougoutController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Api\SwaggerController;
use Illuminate\Support\Facades\Route;

Route::match(['GET', 'HEAD'], '/', [HomeController::class, 'home'])->name('home');
Route::get('/home_pruebas', [HomeController::class, 'homePrueba'])->name('home.pruebas');
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/ingresar', [LoginController::class, 'ingresar'])->name('ingresar');
Route::get('/logout', [LougoutController::class, 'lougout'])->name('logout');
Route::get('/auth_google/status', [LoginController::class, 'authGoogleStatus'])->name('auth.google.status');
Route::post('/auth_google', [LoginController::class, 'authGoogle'])->name('auth.google');

Route::get('/api', [SwaggerController::class, 'ui'])->name('swagger.ui');
Route::get('/api/openapi.json', [SwaggerController::class, 'spec'])->name('swagger.spec');
