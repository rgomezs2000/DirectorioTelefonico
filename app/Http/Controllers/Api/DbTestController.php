<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class DbTestController extends Controller
{
    public function validarLoginTest(Request $request): JsonResponse
    {
        try {
            $login = $request->input('login', $request->input('usuario'));
            $password = $request->input('password', $request->input('clave'));

            if (empty($login) || empty($password)) {
                return response()->json([
                    'codigo' => 422,
                    'mensaje' => 'Debe enviar login/usuario y password/clave en JSON',
                ], 422);
            }

            $resultado = Usuario::validarLoginTest((string) $login, (string) $password);

            return response()->json($resultado, $resultado['codigo']);
        } catch (Throwable $exception) {
            return response()->json([
                'codigo' => 500,
                'mensaje' => 'Error interno del servidor',
                'error' => app()->hasDebugModeEnabled()
                    ? $exception->getMessage()
                    : 'Ocurrió un error inesperado',
            ], 500);
        }
    }
}
