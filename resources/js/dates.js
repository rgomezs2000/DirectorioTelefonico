/**
 * =============================================================
 *  dates.js
 *  Funciones utilitarias para manejo de fechas y horas
 *  Sin librerías externas — usa Intl API nativa del navegador
 *  Stack: Laravel + Blade + Tailwind v4 + Alpine.js + Axios
 * =============================================================
 */

'use strict';

/* -------------------------------------------------------------
   CONSTANTES
------------------------------------------------------------- */
const LOCALE_ES   = 'es-MX';
const ZONA_CDMX   = 'America/Mexico_City';

const MS = {
    segundo: 1_000,
    minuto:  60_000,
    hora:    3_600_000,
    dia:     86_400_000,
    semana:  604_800_000,
    mes:     2_592_000_000,
    anio:    31_536_000_000,
};


/* -------------------------------------------------------------
   1. NOW / TODAY
------------------------------------------------------------- */
/**
 * Retorna el objeto Date de ahora mismo.
 * @returns {Date}
 */
export function now() {
    return new Date();
}

/**
 * Retorna la fecha actual sin hora (00:00:00).
 * @returns {Date}
 */
export function today() {
    const d = new Date();
    d.setHours(0, 0, 0, 0);
    return d;
}


/* -------------------------------------------------------------
   2. PARSE  —  convierte varios formatos a Date
------------------------------------------------------------- */
/**
 * Convierte un valor a objeto Date de forma segura.
 * Acepta: Date, timestamp numérico, string ISO, 'YYYY-MM-DD'.
 * @param {Date|number|string} value
 * @returns {Date|null}
 */
export function parseDate(value) {
    if (!value) return null;
    if (value instanceof Date) return isValid(value) ? value : null;
    if (typeof value === 'number') return new Date(value);
    // 'YYYY-MM-DD' → evitar desfase de zona horaria
    if (typeof value === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(value)) {
        const [y, m, d] = value.split('-').map(Number);
        return new Date(y, m - 1, d);
    }
    const d = new Date(value);
    return isValid(d) ? d : null;
}

/**
 * Verifica si una fecha es válida.
 * @param {Date} date
 * @returns {boolean}
 */
export function isValid(date) {
    return date instanceof Date && !isNaN(date.getTime());
}


/* -------------------------------------------------------------
   3. FORMAT  —  formatos de visualización
------------------------------------------------------------- */
/**
 * Formatea una fecha con Intl.DateTimeFormat.
 * @param {Date|string|number} value
 * @param {Intl.DateTimeFormatOptions} options
 * @param {string} locale  (default: 'es-MX')
 * @returns {string}
 *
 * @example  formatDate('2024-06-15') // => '15 de junio de 2024'
 */
export function formatDate(value, options = {}, locale = LOCALE_ES) {
    const d = parseDate(value);
    if (!d) return '';
    const defaultOpts = { day: 'numeric', month: 'long', year: 'numeric' };
    return new Intl.DateTimeFormat(locale, { ...defaultOpts, ...options }).format(d);
}

/**
 * Formato corto: DD/MM/YYYY
 * @param {Date|string|number} value
 * @returns {string}  ej: '15/06/2024'
 */
export function formatShort(value) {
    const d = parseDate(value);
    if (!d) return '';
    return new Intl.DateTimeFormat(LOCALE_ES, {
        day: '2-digit', month: '2-digit', year: 'numeric',
    }).format(d);
}

/**
 * Formato con hora: DD/MM/YYYY HH:MM
 * @param {Date|string|number} value
 * @param {boolean} seconds  incluir segundos (default: false)
 * @returns {string}  ej: '15/06/2024, 14:30'
 */
export function formatDateTime(value, seconds = false) {
    const d = parseDate(value);
    if (!d) return '';
    return new Intl.DateTimeFormat(LOCALE_ES, {
        day:    '2-digit',
        month:  '2-digit',
        year:   'numeric',
        hour:   '2-digit',
        minute: '2-digit',
        ...(seconds && { second: '2-digit' }),
        hour12: false,
    }).format(d);
}

/**
 * Solo la hora: HH:MM
 * @param {Date|string|number} value
 * @param {boolean} seconds  (default: false)
 * @returns {string}  ej: '14:30'
 */
export function formatTime(value, seconds = false) {
    const d = parseDate(value);
    if (!d) return '';
    return new Intl.DateTimeFormat(LOCALE_ES, {
        hour:   '2-digit',
        minute: '2-digit',
        ...(seconds && { second: '2-digit' }),
        hour12: false,
    }).format(d);
}

/**
 * Día de la semana completo o abreviado.
 * @param {Date|string|number} value
 * @param {'long'|'short'|'narrow'} style  (default: 'long')
 * @returns {string}  ej: 'sábado' | 'sáb'
 */
export function formatWeekday(value, style = 'long') {
    const d = parseDate(value);
    if (!d) return '';
    return new Intl.DateTimeFormat(LOCALE_ES, { weekday: style }).format(d);
}

/**
 * Mes en texto completo o abreviado.
 * @param {Date|string|number} value
 * @param {'long'|'short'|'narrow'} style  (default: 'long')
 * @returns {string}  ej: 'junio' | 'jun'
 */
export function formatMonth(value, style = 'long') {
    const d = parseDate(value);
    if (!d) return '';
    return new Intl.DateTimeFormat(LOCALE_ES, { month: style }).format(d);
}

/**
 * Convierte Date a string ISO: 'YYYY-MM-DD'
 * @param {Date|string|number} value
 * @returns {string}  ej: '2024-06-15'
 */
export function toISODate(value) {
    const d = parseDate(value);
    if (!d) return '';
    const y  = d.getFullYear();
    const m  = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
}


/* -------------------------------------------------------------
   4. RELATIVE TIME  —  "hace X tiempo"
------------------------------------------------------------- */
/**
 * Retorna el tiempo relativo desde una fecha hasta ahora.
 * Usa Intl.RelativeTimeFormat nativo.
 * @param {Date|string|number} value
 * @param {string} locale  (default: 'es-MX')
 * @returns {string}  ej: 'hace 3 días' | 'en 2 horas'
 */
export function timeAgo(value, locale = LOCALE_ES) {
    const d = parseDate(value);
    if (!d) return '';

    const rtf   = new Intl.RelativeTimeFormat(locale, { numeric: 'auto' });
    const diff  = d.getTime() - Date.now();
    const absDiff = Math.abs(diff);

    if (absDiff < MS.minuto)  return rtf.format(Math.round(diff / MS.segundo), 'second');
    if (absDiff < MS.hora)    return rtf.format(Math.round(diff / MS.minuto),  'minute');
    if (absDiff < MS.dia)     return rtf.format(Math.round(diff / MS.hora),    'hour');
    if (absDiff < MS.semana)  return rtf.format(Math.round(diff / MS.dia),     'day');
    if (absDiff < MS.mes)     return rtf.format(Math.round(diff / MS.semana),  'week');
    if (absDiff < MS.anio)    return rtf.format(Math.round(diff / MS.mes),     'month');
    return rtf.format(Math.round(diff / MS.anio), 'year');
}


/* -------------------------------------------------------------
   5. COMPARACIONES
------------------------------------------------------------- */
/**
 * Diferencia entre dos fechas en la unidad indicada.
 * @param {Date|string|number} dateA
 * @param {Date|string|number} dateB
 * @param {'days'|'hours'|'minutes'|'months'|'years'} unit
 * @returns {number}  negativo si dateA < dateB
 */
export function diffDate(dateA, dateB, unit = 'days') {
    const a = parseDate(dateA)?.getTime();
    const b = parseDate(dateB)?.getTime();
    if (!a || !b) return null;
    const diff = a - b;
    const map = { minutes: MS.minuto, hours: MS.hora, days: MS.dia, months: MS.mes, years: MS.anio };
    return Math.round(diff / (map[unit] ?? MS.dia));
}

/**
 * Verifica si una fecha es hoy.
 * @param {Date|string|number} value
 * @returns {boolean}
 */
export function isToday(value) {
    const d = parseDate(value);
    if (!d) return false;
    const t = today();
    return d.getFullYear() === t.getFullYear() &&
           d.getMonth()    === t.getMonth()    &&
           d.getDate()     === t.getDate();
}

/**
 * Verifica si una fecha es del pasado.
 * @param {Date|string|number} value
 * @returns {boolean}
 */
export function isPast(value) {
    const d = parseDate(value);
    return d ? d.getTime() < Date.now() : false;
}

/**
 * Verifica si una fecha es del futuro.
 * @param {Date|string|number} value
 * @returns {boolean}
 */
export function isFuture(value) {
    const d = parseDate(value);
    return d ? d.getTime() > Date.now() : false;
}

/**
 * Verifica si una fecha está dentro de un rango (inclusivo).
 * @param {Date|string|number} value
 * @param {Date|string|number} start
 * @param {Date|string|number} end
 * @returns {boolean}
 */
export function isBetween(value, start, end) {
    const v = parseDate(value)?.getTime();
    const s = parseDate(start)?.getTime();
    const e = parseDate(end)?.getTime();
    if (!v || !s || !e) return false;
    return v >= s && v <= e;
}

/**
 * Verifica si dos fechas son el mismo día.
 * @param {Date|string|number} a
 * @param {Date|string|number} b
 * @returns {boolean}
 */
export function isSameDay(a, b) {
    const da = parseDate(a);
    const db = parseDate(b);
    if (!da || !db) return false;
    return da.getFullYear() === db.getFullYear() &&
           da.getMonth()    === db.getMonth()    &&
           da.getDate()     === db.getDate();
}


/* -------------------------------------------------------------
   6. MANIPULACIÓN  —  sumar / restar / inicio / fin
------------------------------------------------------------- */
/**
 * Suma o resta unidades a una fecha (sin mutar el original).
 * @param {Date|string|number} value
 * @param {number} amount   positivo suma, negativo resta
 * @param {'days'|'months'|'years'|'hours'|'minutes'|'seconds'} unit
 * @returns {Date}
 *
 * @example  addDate(new Date(), 7, 'days')   // +7 días
 * @example  addDate(new Date(), -1, 'months') // mes anterior
 */
export function addDate(value, amount, unit = 'days') {
    const d = new Date(parseDate(value));
    switch (unit) {
        case 'seconds': d.setSeconds(d.getSeconds() + amount); break;
        case 'minutes': d.setMinutes(d.getMinutes() + amount); break;
        case 'hours':   d.setHours(d.getHours() + amount);     break;
        case 'days':    d.setDate(d.getDate() + amount);        break;
        case 'months':  d.setMonth(d.getMonth() + amount);      break;
        case 'years':   d.setFullYear(d.getFullYear() + amount); break;
    }
    return d;
}

/**
 * Inicio del día (00:00:00.000).
 * @param {Date|string|number} value
 * @returns {Date}
 */
export function startOfDay(value) {
    const d = new Date(parseDate(value));
    d.setHours(0, 0, 0, 0);
    return d;
}

/**
 * Fin del día (23:59:59.999).
 * @param {Date|string|number} value
 * @returns {Date}
 */
export function endOfDay(value) {
    const d = new Date(parseDate(value));
    d.setHours(23, 59, 59, 999);
    return d;
}

/**
 * Inicio del mes.
 * @param {Date|string|number} value
 * @returns {Date}
 */
export function startOfMonth(value) {
    const d = parseDate(value);
    return new Date(d.getFullYear(), d.getMonth(), 1);
}

/**
 * Fin del mes (último día).
 * @param {Date|string|number} value
 * @returns {Date}
 */
export function endOfMonth(value) {
    const d = parseDate(value);
    return new Date(d.getFullYear(), d.getMonth() + 1, 0);
}

/**
 * Inicio de la semana (lunes).
 * @param {Date|string|number} value
 * @returns {Date}
 */
export function startOfWeek(value) {
    const d = new Date(parseDate(value));
    const day = d.getDay();
    const diff = day === 0 ? -6 : 1 - day; // lunes = inicio
    d.setDate(d.getDate() + diff);
    d.setHours(0, 0, 0, 0);
    return d;
}


/* -------------------------------------------------------------
   7. RANGO DE FECHAS
------------------------------------------------------------- */
/**
 * Genera un array de fechas entre dos fechas (inclusive).
 * @param {Date|string|number} start
 * @param {Date|string|number} end
 * @returns {Date[]}
 */
export function dateRange(start, end) {
    const result = [];
    let current  = startOfDay(parseDate(start));
    const last   = startOfDay(parseDate(end));
    while (current <= last) {
        result.push(new Date(current));
        current = addDate(current, 1, 'days');
    }
    return result;
}


/* -------------------------------------------------------------
   8. EDAD  —  calcula edad en años
------------------------------------------------------------- */
/**
 * Calcula la edad en años a partir de una fecha de nacimiento.
 * @param {Date|string|number} birthDate
 * @returns {number}
 */
export function age(birthDate) {
    const birth = parseDate(birthDate);
    if (!birth) return null;
    const t   = today();
    let years = t.getFullYear() - birth.getFullYear();
    const m   = t.getMonth() - birth.getMonth();
    if (m < 0 || (m === 0 && t.getDate() < birth.getDate())) years--;
    return years;
}


/* -------------------------------------------------------------
   9. DÍAS HÁBILES
------------------------------------------------------------- */
/**
 * Verifica si una fecha es fin de semana.
 * @param {Date|string|number} value
 * @returns {boolean}
 */
export function isWeekend(value) {
    const d = parseDate(value);
    return d ? [0, 6].includes(d.getDay()) : false;
}

/**
 * Verifica si una fecha es día hábil (lunes–viernes).
 * @param {Date|string|number} value
 * @returns {boolean}
 */
export function isWeekday(value) {
    return !isWeekend(value);
}

/**
 * Cuenta los días hábiles entre dos fechas.
 * @param {Date|string|number} start
 * @param {Date|string|number} end
 * @returns {number}
 */
export function workingDaysBetween(start, end) {
    return dateRange(start, end).filter(isWeekday).length;
}


/* -------------------------------------------------------------
   10. COUNTDOWN  —  tiempo restante hasta una fecha
------------------------------------------------------------- */
/**
 * Retorna el desglose de tiempo restante hasta `target`.
 * Ideal para conectar con Alpine.js y un setInterval.
 * @param {Date|string|number} target
 * @returns {{ dias: number, horas: number, minutos: number, segundos: number, expirado: boolean }}
 *
 * @example  Alpine.js:
 *   x-data="{ timer: countdown('2025-12-31') }"
 *   x-init="setInterval(() => timer = countdown('2025-12-31'), 1000)"
 */
export function countdown(target) {
    const diff = parseDate(target)?.getTime() - Date.now();
    if (!diff || diff <= 0) return { dias: 0, horas: 0, minutos: 0, segundos: 0, expirado: true };
    return {
        dias:     Math.floor(diff / MS.dia),
        horas:    Math.floor((diff % MS.dia)  / MS.hora),
        minutos:  Math.floor((diff % MS.hora) / MS.minuto),
        segundos: Math.floor((diff % MS.minuto) / MS.segundo),
        expirado: false,
    };
}