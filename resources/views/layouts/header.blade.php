@php
    $moduloActual = \App\Helpers\Menu::obtenerModuloActual(request()->getPathInfo());
    $nombreModuloActual = (string) data_get($moduloActual, 'nombre', 'Inicio');
    $breadcrumbItems = data_get($moduloActual, 'breadcrumb', ['Inicio']);
    $breadcrumbItems = is_array($breadcrumbItems) && ! empty($breadcrumbItems) ? $breadcrumbItems : ['Inicio'];
@endphp

<header class="h-[74px] bg-neutral-100 px-4 shadow-sm md:px-6">
    <div class="flex h-full items-center gap-3 md:gap-4">
        <button
            type="button"
            class="inline-flex h-10 w-10 shrink-0 items-center justify-center text-neutral-700 transition hover:text-neutral-900 focus:outline-none focus:ring-2 focus:ring-neutral-400"
            @click="sidebarOpen = !sidebarOpen"
            aria-label="Mostrar u ocultar menú lateral"
        >
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>

        <div class="min-w-0 flex-1">
            <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
                <h2 class="text-[17px] font-semibold leading-tight tracking-wide text-neutral-900">{{ $nombreModuloActual }}</h2>

                <nav class="text-[12px] leading-tight tracking-wide text-neutral-600" aria-label="Breadcrumb">
                    @hasSection('breadcrumb')
                        @yield('breadcrumb')
                    @else
                        @foreach ($breadcrumbItems as $indice => $texto)
                            <span class="{{ $loop->last ? 'font-medium text-neutral-800' : 'text-neutral-500' }}">{{ $texto }}</span>
                            @if (! $loop->last)
                                <span class="mx-1 inline-block align-middle text-neutral-400">&gt;</span>
                            @endif
                        @endforeach
                    @endif
                </nav>
            </div>
        </div>
    </div>
</header>
