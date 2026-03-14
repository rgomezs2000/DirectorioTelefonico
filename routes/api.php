<?php

use App\Http\Controllers\Api\DbTestController;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/db-test/validar-login-test', [DbTestController::class, 'validarLoginTest']);
Route::post('/login/ingresar', [LoginController::class, 'ingresar']);
