(function () {
    'use strict';

    window.functions = {
        debounce(fn, delay = 300) {
            let timer;
            return function (...args) {
                clearTimeout(timer);
                timer = setTimeout(() => fn.apply(this, args), delay);
            };
        },

        getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        },

        buildQueryString(params = {}) {
            const search = new URLSearchParams();
            Object.entries(params).forEach(([key, value]) => {
                if (value !== null && value !== undefined && value !== '') {
                    search.append(key, String(value));
                }
            });
            return search.toString();
        },

        dynamicNow(locale = 'es-PE', options = {}) {
            const date = new Date();
            const formatOptions = Object.keys(options).length > 0
                ? options
                : {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false,
                };

            return new Intl.DateTimeFormat(locale, formatOptions).format(date);
        },
    };
})();
