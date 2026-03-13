<div
    x-data="{
        fallback: {
            open: false,
            data: { title: '', message: '', icon: 'info', cancelText: null, confirmText: 'Aceptar' },
            close() {},
            cancel() {},
            accept() {},
        },
        dialogStore() {
            return this.$store?.dialog ?? this.fallback;
        }
    }"
    x-show="dialogStore().open"
    x-transition.opacity
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
>
    <div
        @click.away="dialogStore().close()"
        class="w-full max-w-md overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-2xl"
    >
        <header class="flex items-center justify-between border-b border-neutral-200 px-5 py-4">
            <h3 class="text-base font-semibold text-neutral-800" x-text="dialogStore().data.title"></h3>
            <button
                type="button"
                class="rounded-md p-1 text-neutral-500 hover:bg-neutral-100 hover:text-neutral-700"
                @click="dialogStore().close()"
                aria-label="Cerrar"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <div class="flex items-start gap-4 px-5 py-5">
            <div class="mt-0.5 shrink-0">
                <svg x-show="dialogStore().data.icon === 'error'" class="h-6 w-6 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.72 3h16.92a2 2 0 0 0 1.72-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                </svg>

                <svg x-show="dialogStore().data.icon === 'warning'" class="h-6 w-6 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.72 3h16.92a2 2 0 0 0 1.72-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                </svg>

                <svg x-show="dialogStore().data.icon === 'info'" class="h-6 w-6 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8h.01M11 12h1v4h1m-1 5a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
                </svg>

                <svg x-show="dialogStore().data.icon === 'confirm'" class="h-6 w-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>

                <svg x-show="dialogStore().data.icon === 'success'" class="h-6 w-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>

            <p class="text-sm leading-6 text-neutral-600" x-text="dialogStore().data.message"></p>
        </div>

        <footer class="flex justify-end gap-2 border-t border-neutral-200 px-5 py-4">
            <button
                x-show="dialogStore().data.cancelText"
                type="button"
                class="rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100"
                @click="dialogStore().cancel()"
            >
                <span x-text="dialogStore().data.cancelText"></span>
            </button>

            <button
                type="button"
                class="rounded-md bg-neutral-800 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-700"
                @click="dialogStore().accept()"
            >
                <span x-text="dialogStore().data.confirmText"></span>
            </button>
        </footer>
    </div>
</div>
