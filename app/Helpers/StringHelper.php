<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class StringHelper
{
    // -------------------------------------------------------------------------
    // ✂️ RECORTE Y TRUNCADO
    // -------------------------------------------------------------------------

    /**
     * Trunca un texto a una longitud máxima.
     *
     * Ejemplo: StringHelper::truncate('Hola mundo', 5) => "Hola ..."
     */
    public static function truncate(string $text, int $length = 100, string $suffix = '...'): string
    {
        return strlen($text) > $length
            ? substr($text, 0, $length) . $suffix
            : $text;
    }

    /**
     * Trunca el texto respetando palabras completas.
     *
     * Ejemplo: StringHelper::words('Hola mundo cruel', 2) => "Hola mundo..."
     */
    public static function words(string $text, int $words = 10, string $suffix = '...'): string
    {
        return Str::words($text, $words, $suffix);
    }

    // -------------------------------------------------------------------------
    // 🔠 TRANSFORMACIONES
    // -------------------------------------------------------------------------

    /**
     * Convierte un string a slug (URL amigable).
     *
     * Ejemplo: StringHelper::slug('Mi Artículo Genial') => "mi-articulo-genial"
     */
    public static function slug(string $text, string $separator = '-'): string
    {
        return Str::slug($text, $separator);
    }

    /**
     * Convierte a Title Case (primera letra de cada palabra en mayúscula).
     *
     * Ejemplo: StringHelper::title('hola mundo') => "Hola Mundo"
     */
    public static function title(string $text): string
    {
        return Str::title($text);
    }

    /**
     * Convierte a camelCase.
     *
     * Ejemplo: StringHelper::camel('hola_mundo') => "holaMundo"
     */
    public static function camel(string $text): string
    {
        return Str::camel($text);
    }

    /**
     * Convierte a snake_case.
     *
     * Ejemplo: StringHelper::snake('HolaMundo') => "hola_mundo"
     */
    public static function snake(string $text): string
    {
        return Str::snake($text);
    }

    /**
     * Convierte a UPPER CASE.
     *
     * Ejemplo: StringHelper::upper('hola') => "HOLA"
     */
    public static function upper(string $text): string
    {
        return Str::upper($text);
    }

    /**
     * Convierte a lower case.
     *
     * Ejemplo: StringHelper::lower('HOLA') => "hola"
     */
    public static function lower(string $text): string
    {
        return Str::lower($text);
    }

    // -------------------------------------------------------------------------
    // 🔍 BÚSQUEDA Y VALIDACIÓN
    // -------------------------------------------------------------------------

    /**
     * Verifica si un string contiene una subcadena (case-insensitive).
     *
     * Ejemplo: StringHelper::contains('Hola Mundo', 'mundo') => true
     */
    public static function contains(string $haystack, string $needle): bool
    {
        return str_contains(strtolower($haystack), strtolower($needle));
    }

    /**
     * Verifica si un string empieza con una subcadena.
     *
     * Ejemplo: StringHelper::startsWith('Hola Mundo', 'Hola') => true
     */
    public static function startsWith(string $text, string $prefix): bool
    {
        return Str::startsWith($text, $prefix);
    }

    /**
     * Verifica si un string termina con una subcadena.
     *
     * Ejemplo: StringHelper::endsWith('Hola Mundo', 'Mundo') => true
     */
    public static function endsWith(string $text, string $suffix): bool
    {
        return Str::endsWith($text, $suffix);
    }

    /**
     * Verifica si un string está vacío o solo tiene espacios.
     *
     * Ejemplo: StringHelper::isEmpty('   ') => true
     */
    public static function isEmpty(string $text): bool
    {
        return trim($text) === '';
    }

    // -------------------------------------------------------------------------
    // 🔢 LONGITUD Y CONTEO
    // -------------------------------------------------------------------------

    /**
     * Retorna la longitud de un string (compatible con UTF-8).
     *
     * Ejemplo: StringHelper::length('Hola') => 4
     */
    public static function length(string $text): int
    {
        return Str::length($text);
    }

    /**
     * Cuenta cuántas veces aparece una subcadena.
     *
     * Ejemplo: StringHelper::countOccurrences('banana', 'a') => 3
     */
    public static function countOccurrences(string $text, string $search): int
    {
        return substr_count($text, $search);
    }

    // -------------------------------------------------------------------------
    // 🧹 LIMPIEZA
    // -------------------------------------------------------------------------

    /**
     * Elimina espacios extra entre palabras y al inicio/fin.
     *
     * Ejemplo: StringHelper::clean('  hola   mundo  ') => "hola mundo"
     */
    public static function clean(string $text): string
    {
        return preg_replace('/\s+/', ' ', trim($text));
    }

    /**
     * Elimina caracteres especiales dejando solo letras, números y espacios.
     *
     * Ejemplo: StringHelper::onlyAlphanumeric('¡Hola! #mundo') => "Hola mundo"
     */
    public static function onlyAlphanumeric(string $text): string
    {
        return preg_replace('/[^a-zA-Z0-9\s]/', '', $text);
    }

    /**
     * Enmascara parte de un string (útil para emails o teléfonos).
     *
     * Ejemplo: StringHelper::mask('user@email.com', 4) => "user***********"
     */
    public static function mask(string $text, int $visibleChars = 4, string $maskChar = '*'): string
    {
        $visible = substr($text, 0, $visibleChars);
        $masked  = str_repeat($maskChar, max(0, strlen($text) - $visibleChars));
        return $visible . $masked;
    }
}