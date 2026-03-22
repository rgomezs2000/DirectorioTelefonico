import './bootstrap';
import { debounce, getCsrfToken, buildQueryString } from './functions.js';
import { slug, truncate, formatCurrency }           from './strings.js';
import { timeAgo, formatDate, countdown }           from './dates.js';
import { dialog, question }                         from './dialog.js';

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

window.openGoogleAuthPopup = function openGoogleAuthPopup(component = null) {
    const clientId = window.googleClientId ?? '';

    if (!window.google?.accounts?.id || !clientId) {
        window.showSystemDialog(
            'error',
            'Acceso al Sistema',
            'Google OAuth no está configurado (falta cargar SDK o client_id).',
        );
        return;
    }

    window.google.accounts.id.initialize({
        client_id: clientId,
        auto_select: false,
        cancel_on_tap_outside: true,
        callback: async (response) => {
            if (component) {
                component.googleLoading = true;
            }

            try {
                const result = await window.initGoogleAuth({ credential: response?.credential ?? null });

                if (component) {
                    component.googleUser = result?.google_user ?? null;
                    component.googleLoggedIn = !!result?.google_user;
                }
            } finally {
                if (component) {
                    component.googleLoading = false;
                }
            }
        },
    });

    window.google.accounts.id.prompt((notification) => {
        if (notification?.isNotDisplayed?.() || notification?.isSkippedMoment?.()) {
            window.showSystemDialog(
                'info',
                'Acceso al Sistema',
                'Google no pudo mostrar el popup. Verifica popups bloqueados o el dominio autorizado.',
            );
        }
    });
};
