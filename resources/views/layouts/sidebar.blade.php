<div x-data="{ menuOpen: false, submenuOpen: false }">
    <div
        class="fixed inset-0 z-30 bg-black/35 lg:hidden"
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        x-cloak
    ></div>

    <aside
        class="fixed inset-y-0 left-0 z-40 flex h-screen w-72 max-w-[85vw] -translate-x-full flex-col bg-neutral-100 shadow-lg transition-transform duration-300 lg:fixed"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        <div class="px-6 py-5 shadow-sm">
            <h1 class="text-center text-[17px] font-semibold leading-tight tracking-wide whitespace-nowrap text-neutral-900">DIRECTORIO TELEFONICO</h1>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4">
            <ul class="space-y-2 text-[14px] text-neutral-800">
                <li>
                    <a href="#" class="block rounded-md px-3 py-3 shadow-sm transition hover:bg-neutral-200">Inicio</a>
                </li>

                <li>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-md px-3 py-3 text-left shadow-sm transition hover:bg-neutral-200"
                        @click="menuOpen = !menuOpen"
                    >
                        <span>Menu</span>
                        <span class="text-sm" x-text="menuOpen ? '▾' : '▸'"></span>
                    </button>

                    <ul class="mt-2 space-y-2 pl-4" x-show="menuOpen" x-collapse>
                        <li>
                            <a href="#" class="block rounded-md px-3 py-2.5 text-[14px] shadow-sm transition hover:bg-neutral-200">Módulo en menu</a>
                        </li>

                        <li>
                            <button
                                type="button"
                                class="flex w-full items-center justify-between rounded-md px-3 py-2.5 text-left text-[14px] shadow-sm transition hover:bg-neutral-200"
                                @click="submenuOpen = !submenuOpen"
                            >
                                <span>Submenu</span>
                                <span class="text-xs" x-text="submenuOpen ? '▾' : '▸'"></span>
                            </button>

                            <ul class="mt-2 space-y-2 pl-4" x-show="submenuOpen" x-collapse>
                                <li>
                                    <a href="#" class="block rounded-md px-3 py-2 text-[14px] shadow-sm transition hover:bg-neutral-200">Módulo C</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="block rounded-md px-3 py-3 shadow-sm transition hover:bg-neutral-200">Cerrar Sesión</a>
                </li>
            </ul>
        </nav>

        <div class="mx-3 mt-auto mb-4 rounded-md px-4 py-4 text-center text-[12px] leading-relaxed text-neutral-700">
            <p>Hola, &lt;Usuario&gt;</p>
            <p>Tu ultima sesión fue en: &lt;Fecha, Hora (ultima sesión)&gt;</p>
            <p>&lt;IP sesion actual&gt;</p>
            <p>&lt;Fecha, hora (actual)&gt;</p>
        </div>
    </aside>
</div>
