(function () {
    'use strict';

    function datatables(config = {}) {
        return {
            endpoint: config.endpoint ?? null,
            rows: Array.isArray(config.rows) ? config.rows : [],
            columns: Array.isArray(config.columns) ? config.columns : [],
            fieldOptions: Array.isArray(config.fieldOptions) ? config.fieldOptions : [],
            searchField: config.searchField ?? '',
            search: '',
            sortBy: null,
            sortDirection: 'asc',
            page: 1,
            perPage: 25,
            selected: new Set(),

            async init() {
                await this.load();

                if (this.fieldOptions.length === 0) {
                    this.fieldOptions = this.columns.map((column) => ({ value: column, label: column }));
                }

                if (!this.searchField && this.fieldOptions.length > 0) {
                    this.searchField = this.fieldOptions[0].value;
                }
            },

            async load() {
                if (!this.endpoint) return;

                const response = await axios.get(this.endpoint);
                const payload = response?.data;

                if (Array.isArray(payload)) {
                    this.rows = payload;
                    return;
                }

                if (Array.isArray(payload?.data)) {
                    this.rows = payload.data;
                    return;
                }

                this.rows = [];
            },

            get filteredRows() {
                const term = String(this.search ?? '').trim().toLowerCase();
                let data = [...this.rows];

                if (term !== '') {
                    if (this.searchField && this.searchField !== '__all__') {
                        data = data.filter((row) => String(row?.[this.searchField] ?? '').toLowerCase().includes(term));
                    } else {
                        data = data.filter((row) => this.columns.some((column) =>
                            String(row?.[column] ?? '').toLowerCase().includes(term)
                        ));
                    }
                }

                if (this.sortBy) {
                    const direction = this.sortDirection === 'desc' ? -1 : 1;
                    data.sort((a, b) => {
                        const left = String(a?.[this.sortBy] ?? '').toLowerCase();
                        const right = String(b?.[this.sortBy] ?? '').toLowerCase();

                        if (left < right) return -1 * direction;
                        if (left > right) return 1 * direction;
                        return 0;
                    });
                }

                return data;
            },

            get totalPages() {
                return Math.max(1, Math.ceil(this.filteredRows.length / this.perPage));
            },

            get paginatedRows() {
                const start = (this.page - 1) * this.perPage;
                return this.filteredRows.slice(start, start + this.perPage);
            },

            get pages() {
                return Array.from({ length: this.totalPages }, (_, i) => i + 1);
            },

            get rangeStart() {
                if (this.filteredRows.length === 0) return 0;
                return (this.page - 1) * this.perPage + 1;
            },

            get rangeEnd() {
                return Math.min(this.page * this.perPage, this.filteredRows.length);
            },

            get allVisibleSelected() {
                if (this.paginatedRows.length === 0) return false;
                return this.paginatedRows.every((row) => this.selected.has(row.id));
            },

            clearFilters() {
                this.search = '';
                if (this.fieldOptions.length > 0) {
                    this.searchField = this.fieldOptions[0].value;
                }
                this.setPage(1);
            },

            toggleSort(column) {
                if (this.sortBy === column) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortBy = column;
                    this.sortDirection = 'asc';
                }
                this.setPage(1);
            },

            setPage(page) {
                const nextPage = Number(page);
                if (Number.isNaN(nextPage)) return;
                this.page = Math.min(this.totalPages, Math.max(1, nextPage));
            },

            isSelected(id) {
                return this.selected.has(id);
            },

            toggleRow(id) {
                if (this.selected.has(id)) {
                    this.selected.delete(id);
                    return;
                }

                this.selected.add(id);
            },

            toggleAll(checked) {
                if (checked) {
                    this.paginatedRows.forEach((row) => this.selected.add(row.id));
                    return;
                }

                this.paginatedRows.forEach((row) => this.selected.delete(row.id));
            },
        };
    }

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

        datatables,
    };

    window.datatables = datatables;
})();
