<?php

use App\Http\Controllers\Api\DbTestController;
use Illuminate\Support\Facades\Route;

Route::post('/db-test/validar-login-test', [DbTestController::class, 'validarLoginTest']);
