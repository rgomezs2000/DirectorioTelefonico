<div>
    <div
        class="fixed inset-0 z-30 bg-black/40 lg:hidden"
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        x-cloak
    ></div>

    <aside
        class="fixed inset-y-0 left-0 z-40 flex w-72 max-w-[85vw] -translate-x-full flex-col border-r-2 border-neutral-900 bg-neutral-100 transition-transform duration-300 lg:static lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
        <div class="border-b-2 border-neutral-900 px-6 py-5">
            <h1 class="text-2xl font-bold tracking-wide text-neutral-900">DIRECTORIO TELEFONICO</h1>
        </div>

        <nav class="flex-1 overflow-y-auto">
            <ul class="text-xl text-neutral-800">
                <li class="border-b-2 border-neutral-900 px-6 py-5">Inicio</li>
                <li class="border-b-2 border-neutral-900 px-6 py-5">Menu</li>
                <li class="border-b-2 border-neutral-900 px-10 py-5">Submenu</li>
                <li class="border-b-2 border-neutral-900 px-10 py-5">Módulo</li>
                <li class="border-b-2 border-neutral-900 px-10 py-5">...</li>
                <li class="border-b-2 border-neutral-900 px-10 py-5">...</li>
                <li class="border-b-2 border-neutral-900 px-10 py-5">....</li>
                <li class="border-b-2 border-neutral-900 px-6 py-5">Cerrar Sesión</li>
            </ul>
        </nav>

        <div class="border-t-2 border-neutral-900 px-6 py-6 text-center text-2xl leading-relaxed text-neutral-800">
            <p>Hola, &lt;Usuario&gt;</p>
            <p>Tu ultima sesión fue en: &lt;Fecha, Hora (ultima sesión)&gt;</p>
            <p>&lt;IP sesion actual&gt;</p>
        </div>
    </aside>
</div>
