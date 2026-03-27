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
    public static function initAPI(
        string $endpoint,
        string $tipo,
        ?array $jsonEntrada = null,
        ?string $tokenBearer = null,
        int|array|null $parametroNID = null
    ): array
    {
        try {
            $metodo = strtoupper(trim($tipo));
            $endpointNormalizado = self::normalizarEndpoint($endpoint, $parametroNID);

            if ($endpointNormalizado === '') {
                return [
                    'ok' => false,
                    'status' => 400,
                    'json' => [
                        'codigo' => 400,
                        'mensaje' => 'Endpoint inválido',
                        'data' => [],
                    ],
                ];
            }

            $request = Http::acceptJson();

            if (! empty($tokenBearer)) {
                $request = $request->withToken($tokenBearer);
            }

            $opciones = [];
            if ($jsonEntrada !== null) {
                $claveDatos = in_array($metodo, ['GET', 'DELETE'], true) ? 'query' : 'json';
                $opciones[$claveDatos] = $jsonEntrada;
            }

            $respuesta = $request->send($metodo, $endpointNormalizado, $opciones);
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

    private static function normalizarEndpoint(string $endpoint, int|array|null $parametroNID = null): string
    {
        $endpoint = trim($endpoint);

        if ($endpoint === '') {
            return '';
        }

        $parametros = self::normalizarParametrosNID($parametroNID);
        $endpoint = self::inyectarParametrosEnEndpoint($endpoint, $parametros);

        if (Str::startsWith($endpoint, ['http://', 'https://'])) {
            return $endpoint;
        }

        $baseUrl = rtrim((string) config('app.url', ''), '/');

        if ($baseUrl === '' && app()->bound('request')) {
            $baseUrl = rtrim((string) request()->root(), '/');
        }

        if ($baseUrl === '' && app()->bound('request')) {
            $baseUrl = rtrim((string) (request()->getSchemeAndHttpHost() . request()->getBaseUrl()), '/');
        }

        if ($baseUrl === '') {
            $baseUrl = rtrim((string) url('/'), '/');
        }

        if ($baseUrl === '') {
            return '';
        }

        return $baseUrl.'/'.ltrim($endpoint, '/');
    }

    /**
     * @return array<int, int|string>
     */
    private static function normalizarParametrosNID(int|array|null $parametroNID = null): array
    {
        if ($parametroNID === null) {
            return [];
        }

        $parametros = is_array($parametroNID) ? $parametroNID : [$parametroNID];

        return array_values(array_filter(
            $parametros,
            static fn ($valor): bool => $valor !== null && $valor !== ''
        ));
    }

    /**
     * Soporta endpoints como /modulo/{id_n1}/{id_n2}...
     *
     * @param  array<int, int|string>  $parametros
     */
    private static function inyectarParametrosEnEndpoint(string $endpoint, array $parametros): string
    {
        if ($parametros === []) {
            return $endpoint;
        }

        foreach ($parametros as $parametro) {
            if (preg_match('/\{[^}]+\}/', $endpoint, $coincidencia) === 1) {
                $endpoint = preg_replace(
                    '/'.preg_quote($coincidencia[0], '/').'/',
                    rawurlencode((string) $parametro),
                    $endpoint,
                    1
                ) ?? $endpoint;
                continue;
            }

            $endpoint = rtrim($endpoint, '/').'/'.rawurlencode((string) $parametro);
        }

        return $endpoint;
    }
}
