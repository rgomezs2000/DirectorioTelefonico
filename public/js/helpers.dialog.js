(function () {
    'use strict';

    const ICONS_BY_TYPE = {
        error: 'error',
        warning: 'warning',
        info: 'info',
        success: 'success',
    };

    window.dialog = function (type = 'info', title = '', message = '') {
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
    };

    window.question = function (title = '', message = '') {
        return {
            open: true,
            type: 'confirm',
            title,
            message,
            icon: 'confirm',
            confirmText: 'Aceptar',
            cancelText: 'Cancelar',
            onClose: null,
            onAccept: async function () {
                // TODO: implementar lógica AJAX según el flujo requerido.
            },
        };
    };
})();
