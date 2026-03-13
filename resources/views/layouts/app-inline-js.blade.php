<script>
    (function () {
        'use strict';

        const ICONS_BY_TYPE = {
            error: 'error',
            warning: 'warning',
            info: 'info',
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
    })();
</script>
