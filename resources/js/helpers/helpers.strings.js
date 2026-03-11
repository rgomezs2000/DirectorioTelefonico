/**
 * =============================================================
 *  helpers.strings.js
 *  Funciones utilitarias para manipulación de cadenas de texto
 *  Stack: Laravel + Blade + Tailwind v4 + Alpine.js + Axios
 * =============================================================
 */

'use strict';

/* -------------------------------------------------------------
   1. CAPITALIZE  —  primera letra en mayúscula
------------------------------------------------------------- */
/**
 * Pone en mayúscula la primera letra de una cadena.
 * @param {string} str
 * @returns {string}
 *
 * @example  capitalize('hola mundo') // => 'Hola mundo'
 */
export function capitalize(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}


/* -------------------------------------------------------------
   2. TITLE CASE  —  capitaliza cada palabra
------------------------------------------------------------- */
/**
 * Capitaliza la primera letra de cada palabra.
 * @param {string} str
 * @returns {string}
 *
 * @example  titleCase('hola mundo feliz') // => 'Hola Mundo Feliz'
 */
export function titleCase(str) {
    if (!str) return '';
    return str.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
}


/* -------------------------------------------------------------
   3. SLUG  —  convierte texto a URL amigable
------------------------------------------------------------- */
/**
 * Genera un slug a partir de un texto.
 * Elimina acentos, caracteres especiales y espacios.
 * @param {string} str
 * @param {string} separator  (default: '-')
 * @returns {string}
 *
 * @example  slug('¡Hola Mundo! Qué bueno') // => 'hola-mundo-que-bueno'
 */
export function slug(str, separator = '-') {
    if (!str) return '';
    return str
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')   // elimina tildes
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')      // elimina caracteres especiales
        .trim()
        .replace(/[\s-]+/g, separator);    // espacios → separador
}


/* -------------------------------------------------------------
   4. TRUNCATE  —  corta texto con elipsis
------------------------------------------------------------- */
/**
 * Trunca una cadena a la longitud indicada y agrega sufijo.
 * @param {string} str
 * @param {number} length   máx de caracteres (default: 100)
 * @param {string} suffix   (default: '…')
 * @returns {string}
 *
 * @example  truncate('Texto muy largo aquí', 10) // => 'Texto muy…'
 */
export function truncate(str, length = 100, suffix = '…') {
    if (!str) return '';
    if (str.length <= length) return str;
    return str.slice(0, length).trimEnd() + suffix;
}


/* -------------------------------------------------------------
   5. STRIP HTML  —  elimina etiquetas HTML
------------------------------------------------------------- */
/**
 * Elimina todas las etiquetas HTML de una cadena.
 * @param {string} html
 * @returns {string}
 *
 * @example  stripHtml('<p>Hola <b>mundo</b></p>') // => 'Hola mundo'
 */
export function stripHtml(html) {
    if (!html) return '';
    return html.replace(/<[^>]*>/g, '');
}


/* -------------------------------------------------------------
   6. ESCAPE HTML  —  escapa caracteres especiales HTML
------------------------------------------------------------- */
/**
 * Escapa caracteres especiales para evitar XSS al insertar en HTML.
 * @param {string} str
 * @returns {string}
 */
export function escapeHtml(str) {
    if (!str) return '';
    const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };
    return str.replace(/[&<>"']/g, c => map[c]);
}


/* -------------------------------------------------------------
   7. HIGHLIGHT  —  resalta ocurrencias de una búsqueda
------------------------------------------------------------- */
/**
 * Envuelve las coincidencias de `query` en una etiqueta <mark>.
 * @param {string} text
 * @param {string} query
 * @param {string} tag    elemento HTML (default: 'mark')
 * @returns {string}  HTML string
 *
 * @example  highlight('Hola Mundo', 'mundo') // => 'Hola <mark>Mundo</mark>'
 */
export function highlight(text, query, tag = 'mark') {
    if (!text || !query) return text ?? '';
    const escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const regex   = new RegExp(`(${escaped})`, 'gi');
    return text.replace(regex, `<${tag}>$1</${tag}>`);
}


/* -------------------------------------------------------------
   8. WORD COUNT / CHAR COUNT
------------------------------------------------------------- */
/**
 * Cuenta las palabras de una cadena.
 * @param {string} str
 * @returns {number}
 */
export function wordCount(str) {
    if (!str || !str.trim()) return 0;
    return str.trim().split(/\s+/).length;
}

/**
 * Cuenta los caracteres (sin espacios si se indica).
 * @param {string}  str
 * @param {boolean} withSpaces  (default: true)
 * @returns {number}
 */
export function charCount(str, withSpaces = true) {
    if (!str) return 0;
    return withSpaces ? str.length : str.replace(/\s/g, '').length;
}


/* -------------------------------------------------------------
   9. READING TIME  —  tiempo estimado de lectura
------------------------------------------------------------- */
/**
 * Estima el tiempo de lectura de un texto.
 * @param {string} text
 * @param {number} wpm  palabras por minuto (default: 200)
 * @returns {string}  ej: '2 min de lectura'
 */
export function readingTime(text, wpm = 200) {
    const words   = wordCount(text);
    const minutes = Math.ceil(words / wpm);
    return `${minutes} min de lectura`;
}


/* -------------------------------------------------------------
   10. INITIALS  —  iniciales de un nombre
------------------------------------------------------------- */
/**
 * Retorna las iniciales de un nombre completo.
 * @param {string} name
 * @param {number} max  máx de iniciales (default: 2)
 * @returns {string}  ej: 'Juan Pérez' → 'JP'
 */
export function initials(name, max = 2) {
    if (!name) return '';
    return name
        .trim()
        .split(/\s+/)
        .slice(0, max)
        .map(w => w.charAt(0).toUpperCase())
        .join('');
}


/* -------------------------------------------------------------
   11. MASK  —  enmascara partes de una cadena
------------------------------------------------------------- */
/**
 * Enmascara una cadena mostrando solo N caracteres al inicio/fin.
 * @param {string} str
 * @param {number} visibleStart  chars visibles al inicio (default: 0)
 * @param {number} visibleEnd    chars visibles al final  (default: 4)
 * @param {string} maskChar      (default: '*')
 * @returns {string}
 *
 * @example  mask('4111111111111234', 0, 4) // => '************1234'
 */
export function mask(str, visibleStart = 0, visibleEnd = 4, maskChar = '*') {
    if (!str) return '';
    const len    = str.length;
    const middle = len - visibleStart - visibleEnd;
    if (middle <= 0) return str;
    return (
        str.slice(0, visibleStart) +
        maskChar.repeat(middle) +
        str.slice(len - visibleEnd)
    );
}


/* -------------------------------------------------------------
   12. FORMAT NUMBER  —  formatea números con separadores
------------------------------------------------------------- */
/**
 * Formatea un número con separadores de miles y decimales.
 * @param {number} value
 * @param {number} decimals    (default: 0)
 * @param {string} locale      (default: 'es-MX')
 * @returns {string}
 *
 * @example  formatNumber(1234567.5, 2) // => '1,234,567.50'
 */
export function formatNumber(value, decimals = 0, locale = 'es-MX') {
    if (value === null || value === undefined) return '';
    return new Intl.NumberFormat(locale, {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    }).format(value);
}


/* -------------------------------------------------------------
   13. FORMAT CURRENCY  —  formatea como moneda
------------------------------------------------------------- */
/**
 * Formatea un número como moneda.
 * @param {number} value
 * @param {string} currency  código ISO 4217 (default: 'MXN')
 * @param {string} locale    (default: 'es-MX')
 * @returns {string}
 *
 * @example  formatCurrency(1500) // => '$1,500.00'
 */
export function formatCurrency(value, currency = 'MXN', locale = 'es-MX') {
    if (value === null || value === undefined) return '';
    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency,
    }).format(value);
}


/* -------------------------------------------------------------
   14. PAD  —  rellena con caracteres
------------------------------------------------------------- */
/**
 * Rellena una cadena por la izquierda o derecha.
 * @param {string|number} value
 * @param {number}        length   longitud total
 * @param {string}        char     carácter de relleno (default: '0')
 * @param {'left'|'right'} side   (default: 'left')
 * @returns {string}
 *
 * @example  pad(5, 3)        // => '005'
 * @example  pad('hi', 5, '-', 'right') // => 'hi---'
 */
export function pad(value, length, char = '0', side = 'left') {
    const str = String(value);
    return side === 'left' ? str.padStart(length, char) : str.padEnd(length, char);
}


/* -------------------------------------------------------------
   15. CAMEL / SNAKE / PASCAL CASE
------------------------------------------------------------- */
/**
 * Convierte una cadena a camelCase.
 * @param {string} str
 * @returns {string}  ej: 'mi variable' → 'miVariable'
 */
export function camelCase(str) {
    if (!str) return '';
    return str
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-zA-Z0-9]+(.)/g, (_, c) => c.toUpperCase())
        .replace(/^./, c => c.toLowerCase());
}

/**
 * Convierte una cadena a snake_case.
 * @param {string} str
 * @returns {string}  ej: 'MiVariable' → 'mi_variable'
 */
export function snakeCase(str) {
    if (!str) return '';
    return str
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/([A-Z])/g, '_$1')
        .replace(/[^a-z0-9_]/gi, '_')
        .replace(/_+/g, '_')
        .replace(/^_|_$/g, '')
        .toLowerCase();
}

/**
 * Convierte una cadena a PascalCase.
 * @param {string} str
 * @returns {string}  ej: 'mi variable' → 'MiVariable'
 */
export function pascalCase(str) {
    if (!str) return '';
    const cc = camelCase(str);
    return cc.charAt(0).toUpperCase() + cc.slice(1);
}


/* -------------------------------------------------------------
   16. CONTAINS / STARTS WITH / ENDS WITH (case-insensitive)
------------------------------------------------------------- */
/**
 * Verifica si `str` contiene `search` (sin importar mayúsculas).
 * @param {string} str
 * @param {string} search
 * @returns {boolean}
 */
export function contains(str, search) {
    if (!str || !search) return false;
    return str.toLowerCase().includes(search.toLowerCase());
}

/**
 * Verifica si `str` empieza con `prefix` (sin importar mayúsculas).
 * @param {string} str
 * @param {string} prefix
 * @returns {boolean}
 */
export function startsWith(str, prefix) {
    return str?.toLowerCase().startsWith(prefix.toLowerCase()) ?? false;
}

/**
 * Verifica si `str` termina con `suffix` (sin importar mayúsculas).
 * @param {string} str
 * @param {string} suffix
 * @returns {boolean}
 */
export function endsWith(str, suffix) {
    return str?.toLowerCase().endsWith(suffix.toLowerCase()) ?? false;
}


/* -------------------------------------------------------------
   17. VALIDATORS  —  validaciones de formato comunes
------------------------------------------------------------- */
/**
 * Valida formato de email.
 * @param {string} email
 * @returns {boolean}
 */
export function isEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email ?? '');
}

/**
 * Valida formato de URL (http/https).
 * @param {string} url
 * @returns {boolean}
 */
export function isUrl(url) {
    try { return ['http:', 'https:'].includes(new URL(url).protocol); }
    catch { return false; }
}

/**
 * Valida que solo contenga letras y números.
 * @param {string} str
 * @returns {boolean}
 */
export function isAlphanumeric(str) {
    return /^[a-zA-Z0-9]+$/.test(str ?? '');
}

/**
 * Valida formato de teléfono mexicano (10 dígitos).
 * @param {string} phone
 * @returns {boolean}
 */
export function isPhoneMX(phone) {
    return /^(\+52)?[\s-]?(\d[\s-]?){10}$/.test((phone ?? '').replace(/\s/g, ''));
}