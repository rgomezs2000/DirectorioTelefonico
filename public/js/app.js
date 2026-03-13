(function () {
    'use strict';

    const ICONS_BY_TYPE = {
        error: 'error',
        warning: 'warning',
        info: 'info',
        success: 'success',
        confirm: 'confirm',
    };

    function createDialog(type = 'info', title = '', message = '') {
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

    window.dialog = window.dialog || createDialog;

    function registerDialogStore() {
        if (!window.Alpine || window.__dialogStoreRegistered) {
            return;
        }

        window.Alpine.store('dialog', {
            open: false,
            data: window.dialog('info', '', ''),

            show(payload) {
                this.data = { ...payload, open: true };
                this.open = true;
            },

            async close() {
                if (typeof this.data.onClose === 'function') {
                    await this.data.onClose();
                }
                this.open = false;
            },

            async cancel() {
                await this.close();
            },

            async accept() {
                if (typeof this.data.onAccept === 'function') {
                    await this.data.onAccept();
                }
                await this.close();
            },
        });

        window.__dialogStoreRegistered = true;
    }

    if (window.Alpine) {
        registerDialogStore();
    }

    document.addEventListener('alpine:init', registerDialogStore);

    window.showSystemDialog = function showSystemDialog(type, title, message) {
        const store = window.Alpine?.store?.('dialog');
        const payload = window.dialog(type, title, message);

        if (store) {
            store.show(payload);
            return;
        }

        window.alert(`${title}\n\n${message}`);
    };

    window.loginAjax = async function loginAjax(component = null) {
        const form = component?.form ?? {};
        const login = String(form.login ?? '').trim();
        const password = String(form.password ?? '').trim();

        if (!login) {
            window.showSystemDialog('info', 'Acceso al Sistema', 'requiere usuario');
            return;
        }

        if (!password) {
            window.showSystemDialog('info', 'Acceso al Sistema', 'requiere contraseña');
            return;
        }

        if (component) {
            component.loading = true;
        }

        try {
            const response = await axios.post('/ingresar', { login, password });
            const result = typeof response.data === 'string'
                ? response.data
                : JSON.stringify(response.data, null, 2);

            window.showSystemDialog('success', 'Acceso al Sistema', result);
        } catch (error) {
            const errorText = error.response?.data
                ? JSON.stringify(error.response.data, null, 2)
                : (error.message ?? 'Error desconocido');

            window.showSystemDialog('error', 'Acceso al Sistema', errorText);
        } finally {
            if (component) {
                component.loading = false;
            }
        }
    };

    window.initGoogleAuth = async function initGoogleAuth(payload = {}) {
        const googlePayload = {
            credential: payload.credential ?? null,
            email: payload.email ?? null,
            name: payload.name ?? null,
            client_id: payload.client_id ?? null,
            token_type: payload.token_type ?? null,
            scope: payload.scope ?? null,
        };

        const response = await axios.post('/auth_google', googlePayload);
        const result = response.data;
        const resultMessage = typeof result === 'string'
            ? result
            : JSON.stringify(result, null, 2);

        window.showSystemDialog('info', 'Prueba Google OAuth', resultMessage);

        return result;
    };
})();
