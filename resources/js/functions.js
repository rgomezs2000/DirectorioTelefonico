/**
 * =============================================================
 *  functions.js
 *  Funciones utilitarias generales para el proyecto
 *  Stack: Laravel + Blade + Tailwind v4 + Alpine.js + Axios
 * =============================================================
 */

'use strict';

/* -------------------------------------------------------------
   1. DEEP CLONE  —  copia profunda de objetos/arrays
------------------------------------------------------------- */
/**
 * Retorna una copia profunda del valor dado.
 * @param {*} value
 * @returns {*}
 */
export function deepClone(value) {
    return JSON.parse(JSON.stringify(value));
}


/* -------------------------------------------------------------
   2. DEEP MERGE  —  fusiona objetos de forma recursiva
------------------------------------------------------------- */
/**
 * Fusiona dos objetos de forma profunda (sin mutar el target).
 * @param {Object} target
 * @param {Object} source
 * @returns {Object}
 */
export function deepMerge(target, source) {
    const result = { ...target };
    for (const key in source) {
        if (source[key] && typeof source[key] === 'object' && !Array.isArray(source[key])) {
            result[key] = deepMerge(result[key] ?? {}, source[key]);
        } else {
            result[key] = source[key];
        }
    }
    return result;
}


/* -------------------------------------------------------------
   3. DEBOUNCE  —  retrasa la ejecución de una función
------------------------------------------------------------- */
/**
 * Retorna una versión con debounce de la función dada.
 * Útil para inputs de búsqueda con Alpine.js.
 * @param {Function} fn
 * @param {number} delay  ms de espera (default: 300)
 * @returns {Function}
 *
 * @example
 * const buscar = debounce((q) => axios.get('/api/search', { params: { q } }), 400);
 */
export function debounce(fn, delay = 300) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), delay);
    };
}


/* -------------------------------------------------------------
   4. THROTTLE  —  limita la frecuencia de ejecución
------------------------------------------------------------- */
/**
 * Ejecuta la función como máximo una vez por intervalo.
 * Útil para eventos scroll/resize.
 * @param {Function} fn
 * @param {number} limit  ms entre ejecuciones (default: 200)
 * @returns {Function}
 */
export function throttle(fn, limit = 200) {
    let inThrottle;
    return function (...args) {
        if (!inThrottle) {
            fn.apply(this, args);
            inThrottle = true;
            setTimeout(() => (inThrottle = false), limit);
        }
    };
}


/* -------------------------------------------------------------
   5. GROUPBY  —  agrupa un array de objetos por una clave
------------------------------------------------------------- */
/**
 * Agrupa un array de objetos por el valor de una clave.
 * @param {Array}  arr
 * @param {string} key
 * @returns {Object}
 *
 * @example
 * groupBy([{cat:'a'},{cat:'b'},{cat:'a'}], 'cat')
 * // => { a: [{cat:'a'},{cat:'a'}], b: [{cat:'b'}] }
 */
export function groupBy(arr, key) {
    return arr.reduce((acc, item) => {
        const group = item[key] ?? 'sin_grupo';
        acc[group] = acc[group] ?? [];
        acc[group].push(item);
        return acc;
    }, {});
}


/* -------------------------------------------------------------
   6. SORT ARRAY OF OBJECTS
------------------------------------------------------------- */
/**
 * Ordena un array de objetos por una clave.
 * @param {Array}   arr
 * @param {string}  key
 * @param {'asc'|'desc'} order
 * @returns {Array}
 */
export function sortBy(arr, key, order = 'asc') {
    return [...arr].sort((a, b) => {
        if (a[key] < b[key]) return order === 'asc' ? -1 :  1;
        if (a[key] > b[key]) return order === 'asc' ?  1 : -1;
        return 0;
    });
}


/* -------------------------------------------------------------
   7. PAGINATE  —  paginación local de un array
------------------------------------------------------------- */
/**
 * Retorna un slice del array para la página dada.
 * @param {Array}  arr
 * @param {number} page     página actual (base 1)
 * @param {number} perPage  elementos por página
 * @returns {{ data: Array, total: number, lastPage: number, from: number, to: number }}
 */
export function paginate(arr, page = 1, perPage = 15) {
    const total    = arr.length;
    const lastPage = Math.ceil(total / perPage);
    const from     = (page - 1) * perPage;
    const to       = Math.min(from + perPage, total);
    return {
        data: arr.slice(from, to),
        total,
        lastPage,
        currentPage: page,
        from: from + 1,
        to,
    };
}


/* -------------------------------------------------------------
   8. UNIQUE  —  valores únicos de un array
------------------------------------------------------------- */
/**
 * Elimina duplicados de un array.
 * Acepta clave para arrays de objetos.
 * @param {Array}   arr
 * @param {string?} key  (opcional) clave de objeto
 * @returns {Array}
 */
export function unique(arr, key = null) {
    if (!key) return [...new Set(arr)];
    const seen = new Set();
    return arr.filter(item => {
        const val = item[key];
        if (seen.has(val)) return false;
        seen.add(val);
        return true;
    });
}


/* -------------------------------------------------------------
   9. FLATTEN  —  aplana arrays anidados
------------------------------------------------------------- */
/**
 * Aplana un array de manera recursiva.
 * @param {Array}  arr
 * @param {number} depth  (default: Infinity)
 * @returns {Array}
 */
export function flatten(arr, depth = Infinity) {
    return arr.flat(depth);
}


/* -------------------------------------------------------------
   10. PICK / OMIT  —  seleccionar / excluir propiedades
------------------------------------------------------------- */
/**
 * Retorna un nuevo objeto solo con las claves indicadas.
 * @param {Object}   obj
 * @param {string[]} keys
 * @returns {Object}
 */
export function pick(obj, keys) {
    return keys.reduce((acc, k) => {
        if (Object.prototype.hasOwnProperty.call(obj, k)) acc[k] = obj[k];
        return acc;
    }, {});
}

/**
 * Retorna un nuevo objeto excluyendo las claves indicadas.
 * @param {Object}   obj
 * @param {string[]} keys
 * @returns {Object}
 */
export function omit(obj, keys) {
    return Object.fromEntries(
        Object.entries(obj).filter(([k]) => !keys.includes(k))
    );
}


/* -------------------------------------------------------------
   11. RANDOM
------------------------------------------------------------- */
/**
 * Entero aleatorio entre min y max (inclusive).
 * @param {number} min
 * @param {number} max
 * @returns {number}
 */
export function randomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

/**
 * Elemento aleatorio de un array.
 * @param {Array} arr
 * @returns {*}
 */
export function randomItem(arr) {
    return arr[randomInt(0, arr.length - 1)];
}


/* -------------------------------------------------------------
   12. IS EMPTY  —  verifica si un valor está vacío
------------------------------------------------------------- */
/**
 * Comprueba si un valor está "vacío":
 * null, undefined, '', [], {} se consideran vacíos.
 * @param {*} value
 * @returns {boolean}
 */
export function isEmpty(value) {
    if (value === null || value === undefined) return true;
    if (typeof value === 'string') return value.trim() === '';
    if (Array.isArray(value)) return value.length === 0;
    if (typeof value === 'object') return Object.keys(value).length === 0;
    return false;
}


/* -------------------------------------------------------------
   13. SLEEP  —  espera asíncrona
------------------------------------------------------------- */
/**
 * Pausa la ejecución por N milisegundos.
 * @param {number} ms
 * @returns {Promise<void>}
 *
 * @example
 * await sleep(500); // espera 0.5 segundos
 */
export function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}


/* -------------------------------------------------------------
   14. COPY TO CLIPBOARD
------------------------------------------------------------- */
/**
 * Copia texto al portapapeles del usuario.
 * @param {string} text
 * @returns {Promise<boolean>}
 */
export async function copyToClipboard(text) {
    try {
        await navigator.clipboard.writeText(text);
        return true;
    } catch {
        return false;
    }
}


/* -------------------------------------------------------------
   15. STORAGE WRAPPER  —  localStorage con JSON automático
------------------------------------------------------------- */
export const storage = {
    /**
     * @param {string} key
     * @param {*}      value
     */
    set(key, value) {
        try { localStorage.setItem(key, JSON.stringify(value)); } catch {}
    },
    /**
     * @param {string} key
     * @param {*}      fallback  valor si no existe
     * @returns {*}
     */
    get(key, fallback = null) {
        try {
            const item = localStorage.getItem(key);
            return item !== null ? JSON.parse(item) : fallback;
        } catch { return fallback; }
    },
    /** @param {string} key */
    remove(key) {
        try { localStorage.removeItem(key); } catch {}
    },
    clear() {
        try { localStorage.clear(); } catch {}
    },
};


/* -------------------------------------------------------------
   16. CSRF TOKEN  —  obtiene el meta token de Laravel
------------------------------------------------------------- */
/**
 * Retorna el CSRF token del meta tag de Laravel.
 * Úsalo en headers de Axios si no está configurado globalmente.
 * @returns {string}
 */
export function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}


/* -------------------------------------------------------------
   17. QUERY PARAMS  —  leer / construir parámetros de URL
------------------------------------------------------------- */
/**
 * Convierte un objeto en query string.
 * @param {Object} params
 * @returns {string}  ej: "?page=1&q=hola"
 */
export function buildQueryString(params) {
    const qs = new URLSearchParams(
        Object.entries(params).filter(([, v]) => v !== null && v !== undefined && v !== '')
    ).toString();
    return qs ? `?${qs}` : '';
}

/**
 * Retorna los query params actuales de la URL como objeto.
 * @returns {Object}
 */
export function getQueryParams() {
    return Object.fromEntries(new URLSearchParams(window.location.search));
}