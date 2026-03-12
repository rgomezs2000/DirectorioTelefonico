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
