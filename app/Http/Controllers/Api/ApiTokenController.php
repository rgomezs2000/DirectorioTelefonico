<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\JsonResponse;
use Throwable;

class ApiTokenController extends Controller
{
    public function apiToken(): JsonResponse
    {
        try {
            $respuesta = ApiToken::obtenerToken();

            return response()->json([
                'codigo' => $respuesta->codigo,
                'mensaje' => $respuesta->mensaje,
                'data' => $respuesta->data,
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
