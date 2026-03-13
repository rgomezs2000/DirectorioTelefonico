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
    };
})();
