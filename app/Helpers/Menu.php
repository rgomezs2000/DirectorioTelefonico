<?php

namespace App\Helpers;

use Throwable;

class Menu
{
    /**
     * Obtiene la estructura de menú para el sidebar desde la API interna.
     */
    public static function listarMenu(): array
    {
        $baseUrls = self::obtenerBaseUrls();

        $respuesta = null;
        foreach ($baseUrls as $baseUrl) {
            $url = $baseUrl.'/api/admin/lista_menu';

            $intento = Api::initAPI($url, 'GET', null, null);

            if (($intento['status'] ?? 500) === 404) {
                continue;
            }

            $respuesta = $intento;
            break;
        }

        if (! is_array($respuesta)) {
            return [];
        }

        $data = $respuesta['json']['data'] ?? [];

        return is_array($data) ? $data : [];
    }

    public static function obtenerModuloActual(?string $ruta = null): array
    {
        try {
            $rutaActual = $ruta ?? (app()->bound('request') ? (string) request()->getPathInfo() : '/');
            $rutaParametrizada = self::normalizarRutaParaApi($rutaActual);

            $baseUrls = self::obtenerBaseUrls();
            foreach ($baseUrls as $baseUrl) {
                $endpoint = $baseUrl.'/api/admin/obtener_modulo';
                if ($rutaParametrizada !== null) {
                    $endpoint .= '/'.$rutaParametrizada;
                }

                $respuesta = Api::initAPI($endpoint, 'GET', null, null);

                if (($respuesta['status'] ?? 500) === 404) {
                    continue;
                }

                $data = $respuesta['json']['data'] ?? [];

                return is_array($data) ? $data : [];
            }

            return [];
        } catch (Throwable) {
            return [];
        }
    }

    private static function normalizarRutaParaApi(string $ruta): ?string
    {
        $ruta = trim($ruta);

        if ($ruta === '' || $ruta === '/') {
            return null;
        }

        $rutaSinPrimerSlash = ltrim($ruta, '/');

        if ($rutaSinPrimerSlash === '') {
            return null;
        }

        return str_replace('/', '-', $rutaSinPrimerSlash);
    }

    /**
     * @return array<int, string>
     */
    private static function obtenerBaseUrls(): array
    {
        $baseUrls = [
            (string) config('app.url', ''),
            (string) url('/'),
        ];

        if (app()->bound('request')) {
            $request = request();
            $baseUrls[] = (string) $request->root();
            $baseUrls[] = (string) ($request->getSchemeAndHttpHost().$request->getBaseUrl());
        }

        $normalizadas = array_map(static fn (string $url): string => rtrim($url, '/'), $baseUrls);

        return array_values(array_unique(array_filter($normalizadas)));
    }
}
