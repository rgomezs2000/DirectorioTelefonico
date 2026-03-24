<?php

use App\Http\Controllers\Api\ApiTokenController;
use App\Http\Controllers\Api\DbTestController;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/db-test/validar-login-test', [DbTestController::class, 'validarLoginTest']);
Route::get('/api_token', [ApiTokenController::class, 'apiToken']);
Route::post('/login/ingresar', [LoginController::class, 'ingresar']);
Route::post('/login/auth_google', [LoginController::class, 'authGoogle']);
