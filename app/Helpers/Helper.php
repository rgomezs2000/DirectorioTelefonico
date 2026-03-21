<?php

namespace App\Helpers;

use App\Models\ApiToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Helper
{
    // -------------------------------------------------------------------------
    // 💰 NÚMEROS Y MONEDA
    // -------------------------------------------------------------------------

    /**
     * Formatea un número como moneda.
     *
     * Ejemplo: Helper::currency(1500.5) => "$1,500.50"
     */
    public static function currency(float $amount, string $symbol = '$', int $decimals = 2): string
    {
        return $symbol . number_format($amount, $decimals, '.', ',');
    }

    /**
     * Formatea un número con separadores de miles.
     *
     * Ejemplo: Helper::formatNumber(1000000) => "1,000,000"
     */
    public static function formatNumber(float $number, int $decimals = 0): string
    {
        return number_format($number, $decimals, '.', ',');
    }

    // -------------------------------------------------------------------------
    // 🌐 RUTAS / UI
    // -------------------------------------------------------------------------

    /**
     * Retorna una clase CSS si la ruta actual coincide con el patrón dado.
     *
     * Ejemplo: Helper::activeRoute('dashboard') => "active" (o "")
     */
    public static function activeRoute(string $route, string $class = 'active'): string
    {
        return request()->routeIs($route) ? $class : '';
    }

    /**
     * Retorna 'Sí' o 'No' según un valor booleano.
     *
     * Ejemplo: Helper::yesNo(true) => "Sí"
     */
    public static function yesNo(bool $value): string
    {
        return $value ? 'Sí' : 'No';
    }

    /**
     * Retorna el valor por defecto si el valor dado es nulo o vacío.
     *
     * Ejemplo: Helper::defaultValue(null, 'Sin nombre') => "Sin nombre"
     */
    public static function defaultValue(mixed $value, mixed $default = '-'): mixed
    {
        return ($value === null || $value === '') ? $default : $value;
    }

    // -------------------------------------------------------------------------
    // 🔐 SEGURIDAD / UTILIDADES
    // -------------------------------------------------------------------------

    /**
     * Genera un código único aleatorio con prefijo opcional.
     *
     * Ejemplo: Helper::generateCode('ORD') => "ORD-A3XK9P2M"
     */
    public static function generateCode(string $prefix = '', int $length = 8): string
    {
        $random = strtoupper(Str::random($length));
        return $prefix ? "{$prefix}-{$random}" : $random;
    }

    /**
     * Limpia y sanitiza un string de caracteres peligrosos.
     *
     * Ejemplo: Helper::sanitize('<script>alert(1)</script>') => ""
     */
    public static function sanitize(string $value): string
    {
        return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Verifica si un valor existe en un array (con tipo estricto).
     *
     * Ejemplo: Helper::inArray('admin', ['admin', 'editor']) => true
     */
    public static function inArray(mixed $value, array $array): bool
    {
        return in_array($value, $array, true);
    }

    /**
     * Obtiene un token API consumiendo el endpoint /api/api_token y devuelve solo el valor del token.
     */
    public static function obtenerToken(): string
    {
        $url = rtrim(config('app.url', ''), '/').'/api/api_token';

        if ($url === '/api/api_token') {
            return '';
        }

        $respuesta = Http::acceptJson()->get($url);

        if (! $respuesta->successful()) {
            return '';
        }

        return (string) data_get($respuesta->json(), 'data.api_token', '');
    }

     /** Valida token de cabecera para endpoints API.*/
    public static function validarTokenHeader(): object
    {
        $tokenHeader = request()->bearerToken()
            ?: (string) request()->header('X-Api-Token', request()->header('api-token', ''));

        if ($tokenHeader === '') {
            return (object) [
                'codigo'  => 309,
                'mensaje' => 'Token Incorrecto',
                'data'    => [],
            ];
        }

        $token = ApiToken::where('api_token', $tokenHeader)->first();

        if (! $token) {
            return (object) [
                'codigo'  => 309,
                'mensaje' => 'Token Incorrecto',
                'data'    => [],
            ];
        }

        if ($token->fecha_fin_token->lessThanOrEqualTo(now())) {
            return (object) [
                'codigo'  => 311,
                'mensaje' => 'Token Expirado',
                'data'    => [],
            ];
        }

        if ((bool) $token->usado) {
            return (object) [
                'codigo'  => 310,
                'mensaje' => 'Token Usado',
                'data'    => [],
            ];
        }

        return (object) [
            'codigo'  => 200,
            'mensaje' => 'Token Válido',
            'data'    => [
                'token' => $tokenHeader,
            ],
        ];
    }
}