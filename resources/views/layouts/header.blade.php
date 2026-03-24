<header class="border-b-2 border-neutral-900 bg-neutral-100">
    <div class="flex items-start gap-4 px-4 py-4 md:items-center md:justify-between md:px-6">
        <button
            type="button"
            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded border border-neutral-700 text-neutral-900 hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-neutral-500 lg:hidden"
            @click="sidebarOpen = !sidebarOpen"
            aria-label="Mostrar u ocultar menú lateral"
        >
            <span class="text-2xl leading-none">☰</span>
        </button>

        <div class="flex-1">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <h2 class="text-2xl font-semibold text-neutral-900">Inicio</h2>

                {{-- Breadcrumb opcional --}}
                <div class="text-lg text-neutral-700">
                    @yield('breadcrumb', 'Inicio > modulo > submodulo > funcion')
                </div>
            </div>
        </div>
    </div>
</header>
