(function () {
    'use strict';

    window.dates = {
        formatDate(value, locale = 'es-ES', options = {}) {
            if (!value) return '';
            const date = value instanceof Date ? value : new Date(value);
            return date.toLocaleDateString(locale, {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                ...options,
            });
        },

        timeAgo(value) {
            if (!value) return '';
            const date = value instanceof Date ? value : new Date(value);
            const seconds = Math.floor((Date.now() - date.getTime()) / 1000);
            if (seconds < 60) return 'hace unos segundos';
            if (seconds < 3600) return `hace ${Math.floor(seconds / 60)} min`;
            if (seconds < 86400) return `hace ${Math.floor(seconds / 3600)} h`;
            return `hace ${Math.floor(seconds / 86400)} d`;
        },

        countdown(targetDate) {
            const target = targetDate instanceof Date ? targetDate : new Date(targetDate);
            const diff = Math.max(target.getTime() - Date.now(), 0);
            const totalSeconds = Math.floor(diff / 1000);
            const days = Math.floor(totalSeconds / 86400);
            const hours = Math.floor((totalSeconds % 86400) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;
            return { days, hours, minutes, seconds, finished: diff === 0 };
        },
    };
})();
