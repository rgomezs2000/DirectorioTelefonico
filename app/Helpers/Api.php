<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class Api
{
    /**
     * Inicializa el consumo de un endpoint y retorna siempre un JSON de salida normalizado.
     */
    public static function initAPI(string $endpoint, string $tipo, ?array $jsonEntrada = null, ?string $tokenBearer = null): array
    {
        try {
            $metodo = strtoupper(trim($tipo));
            $request = Http::acceptJson();

            if (! empty($tokenBearer)) {
                $request = $request->withToken($tokenBearer);
            }

            $opciones = [];
            if ($jsonEntrada !== null) {
                $claveDatos = in_array($metodo, ['GET', 'DELETE'], true) ? 'query' : 'json';
                $opciones[$claveDatos] = $jsonEntrada;
            }

            $respuesta = $request->send($metodo, $endpoint, $opciones);
            $jsonSalida = $respuesta->json();

            return [
                'ok' => $respuesta->successful(),
                'status' => $respuesta->status(),
                'json' => is_array($jsonSalida)
                    ? $jsonSalida
                    : [
                        'codigo' => $respuesta->status(),
                        'mensaje' => 'La API devolvió una respuesta no JSON',
                        'data' => [],
                    ],
            ];
        } catch (Throwable $exception) {
            return [
                'ok' => false,
                'status' => 500,
                'json' => [
                    'codigo' => 500,
                    'mensaje' => 'Error al consumir endpoint',
                    'error' => Str::limit(trim((string) $exception->getMessage()) ?: 'Error inesperado', 200),
                    'data' => [],
                ],
            ];
        }
    }
}
