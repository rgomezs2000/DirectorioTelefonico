(function () {
    'use strict';

    function registerDialogStore() {
        if (!window.Alpine || window.__dialogStoreRegistered || typeof window.dialog !== 'function') {
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
})();
