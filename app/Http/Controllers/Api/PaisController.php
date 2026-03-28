<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Pais;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class PaisController extends Controller
{
    public function listarPaises(Request $request, ?string $campo = null, ?string $palabra = null): JsonResponse
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

            $paises = Pais::listarPaises($campo, $palabra);

            $tokenHeader = (string) ($request->bearerToken()
                ?: $request->header('X-Api-Token', $request->header('api-token', '')));
            Helper::tokenUsado($tokenHeader);

            if ($paises->isEmpty()) {
                return response()->json([
                    'codigo' => 408,
                    'mensaje' => 'No existen registros',
                    'data' => [],
                ]);
            }

            return response()->json([
                'codigo' => 200,
                'mensaje' => 'Registros encontrados',
                'data' => $paises,
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
