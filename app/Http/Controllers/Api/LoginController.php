<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class LoginController extends Controller
{
    public function ingresar(Request $request): JsonResponse
    {
        try {
            $validacionToken = Helper::validarTokenHeader();

            if (($validacionToken->codigo ?? null) !== 200) {
                return response()->json([
                    'codigo' => $validacionToken->codigo ?? 309,
                    'mensaje' => $validacionToken->mensaje ?? 'Token Incorrecto',
                    'data' => [],
                ]);
            }

            $login = (string) $request->input('login', '');
            $password = (string) $request->input('password', '');

            $respuestaLogin = Usuario::validarLogin($login, $password);

            if (($respuestaLogin->codigo ?? null) !== 200 || empty($respuestaLogin->data)) {
                return response()->json([
                    'codigo' => $respuestaLogin->codigo ?? 408,
                    'mensaje' => $respuestaLogin->mensaje ?? 'Credenciales inválidas',
                    'data' => [],
                ]);
            }

            return response()->json([
                'codigo' => 200,
                'mensaje' => 'login correcto',
                'data' => [
                    'usuario' => $respuestaLogin->data,
                ],
            ]);
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
