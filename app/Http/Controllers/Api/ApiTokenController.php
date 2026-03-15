<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\JsonResponse;

class ApiTokenController extends Controller
{
    public function apiToken(): JsonResponse
    {
        $respuesta = ApiToken::obtenerToken();

        return response()->json([
            'codigo' => $respuesta->codigo,
            'mensaje' => $respuesta->mensaje,
            'data' => $respuesta->data,
        ]);
    }
}
