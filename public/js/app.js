(function () {
    'use strict';

    window.showSystemDialog = function showSystemDialog(type, title, message) {
        const payload = window.dialog(type, title, message);
        const store = window.Alpine?.store?.('dialog');

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
