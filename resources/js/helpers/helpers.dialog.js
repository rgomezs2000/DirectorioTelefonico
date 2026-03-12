/**
 * =============================================================
 *  helpers.dialog.js
 *  Helpers de configuración para modal global (Alpine.js)
 * =============================================================
 */

'use strict';

const ICONS_BY_TYPE = {
    error: 'error',
    warning: 'warning',
    info: 'info',
};

/**
 * Construye la configuración base para diálogos informativos.
 * Nota: no contempla confirmación (usar question()).
 *
 * @param {'error'|'warning'|'info'} type
 * @param {string} title
 * @param {string} message
 * @returns {{
 *  open: boolean,
 *  type: 'error'|'warning'|'info',
 *  title: string,
 *  message: string,
 *  icon: string,
 *  confirmText: string,
 *  cancelText: string|null,
 *  onAccept: null,
 *  onClose: null
 * }}
 */
export function dialog(type = 'info', title = '', message = '') {
    const normalizedType = ICONS_BY_TYPE[type] ? type : 'info';

    return {
        open: true,
        type: normalizedType,
        title,
        message,
        icon: ICONS_BY_TYPE[normalizedType],
        confirmText: 'Aceptar',
        cancelText: null,
        onAccept: null,
        onClose: null,
    };
}

/**
 * Construye la configuración para diálogos de confirmación.
 * El callback onAccept queda intencionalmente vacío para lógica AJAX.
 *
 * @param {string} title
 * @param {string} message
 * @returns {{
 *  open: boolean,
 *  type: 'confirm',
 *  title: string,
 *  message: string,
 *  icon: 'confirm',
 *  confirmText: string,
 *  cancelText: string,
 *  onAccept: Function,
 *  onClose: null
 * }}
 */
export function question(title = '', message = '') {
    return {
        open: true,
        type: 'confirm',
        title,
        message,
        icon: 'confirm',
        confirmText: 'Aceptar',
        cancelText: 'Cancelar',
        onClose: null,
        onAccept: async () => {
            // TODO: implementar lógica AJAX según el flujo requerido.
        },
    };
}
