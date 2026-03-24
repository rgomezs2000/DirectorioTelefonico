<header class="bg-neutral-100 px-4 py-4 shadow-sm md:px-6">
    <div class="flex items-start gap-3 md:items-center md:gap-4">
        <button
            type="button"
            class="inline-flex h-10 w-10 shrink-0 items-center justify-center text-neutral-700 transition hover:text-neutral-900 focus:outline-none focus:ring-2 focus:ring-neutral-400"
            @click="sidebarOpen = !sidebarOpen"
            aria-label="Mostrar u ocultar menú lateral"
        >
            <span class="text-[1.8rem] leading-none">☰</span>
        </button>

        <div class="min-w-0 flex-1">
            <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
                <h2 class="text-2xl font-semibold text-neutral-900">Inicio</h2>

                <nav class="text-xs text-neutral-600 sm:text-sm" aria-label="Breadcrumb">
                    @yield('breadcrumb')
                    @hasSection('breadcrumb')
                    @else
                        <a href="#" class="hover:text-neutral-900">Inicio</a>
                        <span class="mx-1">&gt;</span>
                        <a href="#" class="hover:text-neutral-900">Modulo</a>
                        <span class="mx-1">&gt;</span>
                        <a href="#" class="hover:text-neutral-900">Submodulo</a>
                        <span class="mx-1">&gt;</span>
                        <span>funcion</span>
                    @endif
                </nav>
            </div>
        </div>
    </div>
</header>
