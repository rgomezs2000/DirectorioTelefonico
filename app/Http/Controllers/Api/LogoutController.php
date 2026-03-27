<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Sesion;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Throwable;

class LogoutController extends Controller
{
    public function setUltimoAcceso(int $id_usuario): JsonResponse
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

            $actualizado = Usuario::setUltimoAcceso($id_usuario);

            $tokenHeader = (string) (request()->bearerToken()
                ?: request()->header('X-Api-Token', request()->header('api-token', '')));
            Helper::tokenUsado($tokenHeader);

            return response()->json([
                'codigo' => 200,
                'mensaje' => 'ultimo acceso fue el: '.now(),
                'data' => [
                    'actualizado' => $actualizado,
                ],
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'codigo' => 500,
                'mensaje' => 'Error del servidor',
                'error' => Str::limit(trim((string) $exception->getMessage()) ?: 'Error inesperado', 120),
            ], 500);
        }
    }

    public function cerrarSesion(int $id_usuario, int $id_sesion): JsonResponse
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

            $actualizado = Sesion::cerrarSesion($id_usuario, $id_sesion);

            $tokenHeader = (string) (request()->bearerToken()
                ?: request()->header('X-Api-Token', request()->header('api-token', '')));
            Helper::tokenUsado($tokenHeader);

            return response()->json([
                'codigo' => 200,
                'mensaje' => 'Sesion Cerrada',
                'data' => [
                    'actualizado' => $actualizado,
                ],
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'codigo' => 500,
                'mensaje' => 'Error del servidor',
                'error' => Str::limit(trim((string) $exception->getMessage()) ?: 'Error inesperado', 120),
            ], 500);
        }
    }
}
