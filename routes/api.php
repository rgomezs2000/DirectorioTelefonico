<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ApiTokenController;
use App\Http\Controllers\Api\DbTestController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\PaisController;
use Illuminate\Support\Facades\Route;

Route::post('/db-test/validar-login-test', [DbTestController::class, 'validarLoginTest']);
Route::get('/api_token', [ApiTokenController::class, 'apiToken']);
Route::post('/login/ingresar', [LoginController::class, 'ingresar']);
Route::post('/login/auth_google', [LoginController::class, 'authGoogle']);
Route::post('/login/registrar_sesion/{id_usuario}', [LoginController::class, 'registrarSesion']);
Route::post('/logout/ultimo_acceso/{id_usuario}', [LogoutController::class, 'setUltimoAcceso']);
Route::post('/logout/{id_usuario}/{id_sesion}', [LogoutController::class, 'cerrarSesion']);

Route::get('/admin/lista_menu', [AdminController::class, 'listarMenu']);

Route::get('/admin/obtener_modulo', [AdminController::class, 'obtenerModulo']);
Route::get('/admin/obtener_modulo/{ruta}', [AdminController::class, 'obtenerModulo']);

Route::get('/maestros/paises/lista_paises/{campo?}/{palabra?}', [PaisController::class, 'listarPaises']);
