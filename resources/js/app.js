import './bootstrap';
import { debounce, getCsrfToken, buildQueryString } from './helpers/helpers.general.js';
import { slug, truncate, formatCurrency }           from './helpers/helpers.strings.js';
import { timeAgo, formatDate, countdown }           from './helpers/helpers.dates.js';
import { dialog, question }                      from './helpers/helpers.dialog.js';

window.dialog = dialog;
window.question = question;

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

    const payloadDialog = dialog('info', 'Prueba Google OAuth', resultMessage);

    if (window.Alpine?.store('dialog')) {
        window.Alpine.store('dialog').show(payloadDialog);
    }

    return result;
};