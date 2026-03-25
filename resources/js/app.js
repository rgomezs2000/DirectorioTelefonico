import './bootstrap';
import { debounce, getCsrfToken, buildQueryString } from './functions.js';
import { slug, truncate, formatCurrency }           from './strings.js';
import { timeAgo, formatDate, countdown }           from './dates.js';
import { dialog, question }                         from './dialog.js';
// Nota: la pantalla de login actualmente usa public/js/app.js directo por CDN.
// Mantener sincronizados los helpers de Google OAuth en ambos archivos.

window.dialog = dialog;
window.question = question;
window.showAjaxSystemDialog = showAjaxSystemDialog;

function registerDialogStore() {
    if (!window.Alpine || window.__dialogStoreRegistered) {
        return;
    }

    window.Alpine.store('dialog', {
        open: false,
        data: dialog('info', '', ''),

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


function getAppRoute(name, fallback = '') {
    const routeValue = window.AppRoutes?.[name];

    if (typeof routeValue === 'string' && routeValue.length > 0) {
        return routeValue;
    }

    return fallback;
}

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
    const focusInput = (inputId) => {
        const input = document.getElementById(inputId);

        if (input && typeof input.focus === 'function') {
            input.focus();
        }
    };

    if (!login) {
        window.showSystemDialog('info', 'Acceso al Sistema', 'requiere usuario');
        focusInput('login');
        return;
    }

    if (!password) {
        window.showSystemDialog('info', 'Acceso al Sistema', 'requiere contraseña');
        focusInput('password');
        return;
    }

    if (component) {
        component.loading = true;
    }

    try {
        const response = await axios.post(getAppRoute('ingresar', '/ingresar'), { login, password });
        const data = response?.data ?? {};
        const codigo = Number(data?.codigo ?? 0);
        const mensaje = String(data?.mensaje ?? '').toLowerCase();
        const loginExitoso = response.status === 200
            || codigo === 200
            || mensaje.includes('exitoso');

        if (loginExitoso) {
            window.location.assign(getAppRoute('home', '/'));
            return;
        }

        const result = typeof data === 'string'
            ? data
            : JSON.stringify(data, null, 2);

        window.showSystemDialog('error', 'Acceso al Sistema', result);
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

    try {
        const response = await axios.post(getAppRoute('authGoogle', '/auth_google'), googlePayload);
        const result = response.data;
        const resultMessage = typeof result === 'string'
            ? result
            : JSON.stringify(result, null, 2);

        showAjaxSystemDialog({
            ok: true,
            title: 'Acceso al Sistema',
            message: resultMessage,
        });

        return result;
    } catch (error) {
        const errorMessage = error.response?.data
            ? JSON.stringify(error.response.data, null, 2)
            : (error.message ?? 'Error desconocido');

        showAjaxSystemDialog({
            ok: false,
            title: 'Acceso al Sistema',
            message: errorMessage,
        });

        throw error;
    }
};

window.fetchGoogleSessionStatus = async function fetchGoogleSessionStatus() {
    try {
        const response = await axios.get(getAppRoute('authGoogleStatus', '/auth_google/status'));
        return response.data ?? { ok: false, is_logged_in: false };
    } catch (error) {
        return {
            ok: false,
            is_logged_in: false,
            message: error.message ?? 'No se pudo obtener el estado de sesión de Google',
        };
    }
};

window.handleCredentialResponse = async function handleCredentialResponse(response) {
    const credential = response?.credential ?? '';
    const route = getAppRoute('authGoogle', '/auth_google');

    if (!credential) {
        window.showSystemDialog(
            'error',
            'Acceso al Sistema',
            'Google OAuth no devolvió credential (id_token).',
        );
        return;
    }

    const payload = new URLSearchParams({
        id_token: credential,
        credential,
    });

    try {
        const serverResponse = await fetch(route, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: payload.toString(),
        });

        const rawText = await serverResponse.text();
        let data = null;

        try {
            data = rawText ? JSON.parse(rawText) : null;
        } catch {
            data = null;
        }

        if (!serverResponse.ok) {
            const backendMessage = data?.message ?? rawText ?? `Error HTTP ${serverResponse.status}`;
            window.showSystemDialog(
                'error',
                'Acceso al Sistema',
                `Google OAuth rechazado por backend.\nHTTP: ${serverResponse.status}\nDetalle: ${backendMessage}`,
            );
            return;
        }

        const successPayload = data ?? { ok: true, message: rawText || 'Google OAuth validado correctamente' };
        window.showSystemDialog('success', 'Acceso al Sistema', JSON.stringify(successPayload, null, 2));
    } catch (error) {
        window.showSystemDialog(
            'error',
            'Acceso al Sistema',
            `Error de red al validar OAuth con backend: ${error.message ?? 'Error desconocido'}`,
        );
    }
};
