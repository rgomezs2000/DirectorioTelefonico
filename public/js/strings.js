(function () {
    'use strict';

    window.strings = {
        slug(text = '') {
            return String(text)
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        },

        truncate(text = '', length = 100, suffix = '…') {
            const value = String(text);
            return value.length > length ? `${value.slice(0, length)}${suffix}` : value;
        },

        formatCurrency(value, locale = 'es-BO', currency = 'BOB') {
            const amount = Number(value || 0);
            return new Intl.NumberFormat(locale, {
                style: 'currency',
                currency,
            }).format(amount);
        },
    };
})();
