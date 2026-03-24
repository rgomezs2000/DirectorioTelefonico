<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
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
                'mensaje' => 'Error del servidor',
                'error' => Str::limit(trim((string) $exception->getMessage()) ?: 'Error inesperado', 120),
            ], 500);
        }
    }
}
