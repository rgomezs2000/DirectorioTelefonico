<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    // -------------------------------------------------------------------------
    // 📅 FORMATEO
    // -------------------------------------------------------------------------

    /**
     * Retorna la fecha actual formateada.
     *
     * Ejemplo: DateHelper::today() => "25/12/2025"
     */
    public static function today(string $format = 'd/m/Y'): string
    {
        return now()->format($format);
    }

    /**
     * Formatea cualquier fecha al formato indicado.
     *
     * Ejemplo: DateHelper::format('2025-01-15', 'd/m/Y') => "15/01/2025"
     */
    public static function format(string|Carbon $date, string $format = 'd/m/Y'): string
    {
        return Carbon::parse($date)->format($format);
    }

    /**
     * Formatea una fecha con hora.
     *
     * Ejemplo: DateHelper::formatDateTime('2025-01-15 14:30:00') => "15/01/2025 14:30"
     */
    public static function formatDateTime(string|Carbon $date, string $format = 'd/m/Y H:i'): string
    {
        return Carbon::parse($date)->format($format);
    }

    // -------------------------------------------------------------------------
    // ⏱️ DIFERENCIAS Y RELATIVOS
    // -------------------------------------------------------------------------

    /**
     * Retorna hace cuánto tiempo ocurrió una fecha en lenguaje humano.
     *
     * Ejemplo: DateHelper::timeAgo('2025-01-01') => "hace 2 meses"
     */
    public static function timeAgo(string|Carbon $date): string
    {
        return Carbon::parse($date)->diffForHumans();
    }

    /**
     * Retorna la diferencia en días entre dos fechas.
     *
     * Ejemplo: DateHelper::diffInDays('2025-01-01', '2025-01-15') => 14
     */
    public static function diffInDays(string|Carbon $from, string|Carbon $to = null): int
    {
        $to = $to ? Carbon::parse($to) : now();
        return (int) Carbon::parse($from)->diffInDays($to);
    }

    /**
     * Retorna la diferencia en horas entre dos fechas.
     *
     * Ejemplo: DateHelper::diffInHours('2025-01-01 08:00', '2025-01-01 14:00') => 6
     */
    public static function diffInHours(string|Carbon $from, string|Carbon $to = null): int
    {
        $to = $to ? Carbon::parse($to) : now();
        return (int) Carbon::parse($from)->diffInHours($to);
    }

    // -------------------------------------------------------------------------
    // ✅ VALIDACIONES
    // -------------------------------------------------------------------------

    /**
     * Verifica si una fecha ya pasó.
     *
     * Ejemplo: DateHelper::isPast('2020-01-01') => true
     */
    public static function isPast(string|Carbon $date): bool
    {
        return Carbon::parse($date)->isPast();
    }

    /**
     * Verifica si una fecha es futura.
     *
     * Ejemplo: DateHelper::isFuture('2099-01-01') => true
     */
    public static function isFuture(string|Carbon $date): bool
    {
        return Carbon::parse($date)->isFuture();
    }

    /**
     * Verifica si una fecha es hoy.
     *
     * Ejemplo: DateHelper::isToday(now()) => true
     */
    public static function isToday(string|Carbon $date): bool
    {
        return Carbon::parse($date)->isToday();
    }

    // -------------------------------------------------------------------------
    // ➕ MANIPULACIÓN
    // -------------------------------------------------------------------------

    /**
     * Suma días a una fecha.
     *
     * Ejemplo: DateHelper::addDays('2025-01-01', 10) => Carbon instance
     */
    public static function addDays(string|Carbon $date, int $days): Carbon
    {
        return Carbon::parse($date)->addDays($days);
    }

    /**
     * Resta días a una fecha.
     *
     * Ejemplo: DateHelper::subDays('2025-01-15', 5) => Carbon instance
     */
    public static function subDays(string|Carbon $date, int $days): Carbon
    {
        return Carbon::parse($date)->subDays($days);
    }

    /**
     * Retorna el inicio del día para una fecha dada.
     *
     * Ejemplo: DateHelper::startOfDay('2025-01-15') => "2025-01-15 00:00:00"
     */
    public static function startOfDay(string|Carbon $date = null): Carbon
    {
        return Carbon::parse($date ?? now())->startOfDay();
    }

    /**
     * Retorna el fin del día para una fecha dada.
     *
     * Ejemplo: DateHelper::endOfDay('2025-01-15') => "2025-01-15 23:59:59"
     */
    public static function endOfDay(string|Carbon $date = null): Carbon
    {
        return Carbon::parse($date ?? now())->endOfDay();
    }
}