<?php

namespace App\Helpers;

use App\Helpers\Api;
use App\Models\ApiToken;
use Illuminate\Http\Request;
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
    public static function obtenerToken(?Request $request = null): string
    {
        $baseUrls = [];

        if ($request !== null) {
            $baseUrls[] = $request->getSchemeAndHttpHost();
        }

        $baseUrls[] = (string) config('app.url', '');
        $baseUrls[] = (string) url('/');
        $baseUrls = array_values(array_unique(array_filter(array_map(
            static fn (string $url): string => rtrim($url, '/'),
            $baseUrls
        ))));

        foreach ($baseUrls as $baseUrl) {
            $url = rtrim($baseUrl, '/') . '/api/api_token';

            if ($url === '/api/api_token') {
                continue;
            }

            $respuesta = Api::initAPI($url, 'GET');

            if (! ($respuesta['ok'] ?? false)) {
                continue;
            }

            $payload = $respuesta['json'] ?? [];
            $token = (string) (
                data_get($payload, 'data.api_token')
                ?? data_get($payload, 'api_token')
                ?? ''
            );

            if ($token !== '') {
                return $token;
            }
        }

        return '';
    }

    /**
     * Obtiene y reutiliza el bearer token en sesión para consumo de APIs desde controladores Web.
     * Si $forzarRenovacion es true, elimina el token en sesión y solicita uno nuevo.
     */
    public static function obtenerBearerTokenDesdeSesion(Request $request, bool $forzarRenovacion = false): string
    {
        if ($forzarRenovacion) {
            $request->session()->forget('api_bearer_token');
        }

        $tokenEnSesion = (string) $request->session()->get('api_bearer_token', '');

        if ($tokenEnSesion !== '') {
            return $tokenEnSesion;
        }

        $token = self::obtenerToken($request);

        if ($token !== '') {
            $request->session()->put('api_bearer_token', $token);
        }

        return $token;
    }

 
    /** Marca como usado un token API. */
    public static function tokenUsado(string $token): bool
    {
        if ($token === '') {
            return false;
        }

        return ApiToken::tokenUsado($token);
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
            ApiToken::tokenUsado($tokenHeader);

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
