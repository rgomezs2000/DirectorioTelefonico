<?php

namespace App\Helpers;

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
