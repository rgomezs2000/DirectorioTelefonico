import './bootstrap';
import { debounce, getCsrfToken, buildQueryString } from './helpers/helpers.general.js';
import { slug, truncate, formatCurrency }           from './helpers/helpers.strings.js';
import { timeAgo, formatDate, countdown }           from './helpers/helpers.dates.js';
import { dialog, question }                      from './helpers/helpers.dialog.js';

window.dialog = dialog;
window.question = question;

document.addEventListener('alpine:init', () => {
    Alpine.store('dialog', {
        open: false,
        data: dialog('info', '', ''),

        show(payload) {
            this.data = { ...payload, open: true };
            this.open = true;
        },

        close() {
            this.open = false;
        },

        cancel() {
            this.close();
        },

        async accept() {
            if (typeof this.data.onAccept === 'function') {
                await this.data.onAccept();
            }
            this.close();
        },
    });
});
