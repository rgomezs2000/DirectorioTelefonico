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

        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="text-[14px] text-neutral-800">
                <li>
                    <a href="#" class="block px-4 py-3 shadow-[inset_0_-1px_0_0_rgba(0,0,0,0.12)] transition hover:bg-neutral-200">Inicio</a>
                </li>

                <li>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between px-4 py-3 text-left shadow-[inset_0_-1px_0_0_rgba(0,0,0,0.12)] transition hover:bg-neutral-200"
                        @click="menuOpen = !menuOpen"
                    >
                        <span>Menu</span>
                        <span class="text-sm" x-text="menuOpen ? '▾' : '▸'"></span>
                    </button>

                    <ul x-show="menuOpen" x-collapse>
                        <li>
                            <a href="#" class="block py-2.5 pr-4 pl-8 text-[14px] shadow-[inset_0_-1px_0_0_rgba(0,0,0,0.12)] transition hover:bg-neutral-200">Módulo en menu</a>
                        </li>

                        <li>
                            <button
                                type="button"
                                class="flex w-full items-center justify-between py-2.5 pr-4 pl-8 text-left text-[14px] shadow-[inset_0_-1px_0_0_rgba(0,0,0,0.12)] transition hover:bg-neutral-200"
                                @click="submenuOpen = !submenuOpen"
                            >
                                <span>Submenu</span>
                                <span class="text-xs" x-text="submenuOpen ? '▾' : '▸'"></span>
                            </button>

                            <ul x-show="submenuOpen" x-collapse>
                                <li>
                                    <a href="#" class="block py-2 pr-4 pl-12 text-[14px] shadow-[inset_0_-1px_0_0_rgba(0,0,0,0.12)] transition hover:bg-neutral-200">Módulo C</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="block px-4 py-3 shadow-[inset_0_-1px_0_0_rgba(0,0,0,0.12)] transition hover:bg-neutral-200">Cerrar Sesión</a>
                </li>
            </ul>
        </nav>

        <div class="mt-auto w-full border-t border-neutral-300 px-4 py-4 text-center text-[12px] leading-relaxed text-neutral-700 shadow-[0_-1px_0_0_rgba(0,0,0,0.1)]">
            <p>Hola, &lt;Usuario&gt;</p>
            <p>Tu ultima sesión fue en: &lt;Fecha, Hora (ultima sesión)&gt;</p>
            <p>&lt;IP sesion actual&gt;</p>
            <p>&lt;Fecha, hora (actual)&gt;</p>
        </div>
    </aside>
</div>
