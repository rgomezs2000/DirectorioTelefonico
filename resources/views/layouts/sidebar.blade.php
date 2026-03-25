@php
    $menus = \App\Helpers\Menu::listarMenu();


    $baseUrl = rtrim((string) url('/'), '/');

    $resolverRuta = static function (?string $ruta, string $baseUrl): string {
        $rutaNormalizada = trim((string) $ruta);

        if ($rutaNormalizada === '') {
            return '#';
        }

        if (str_starts_with($rutaNormalizada, 'http://') || str_starts_with($rutaNormalizada, 'https://')) {
            return $rutaNormalizada;
        }

        return $baseUrl.'/'.ltrim($rutaNormalizada, '/');
    };
    $resolverIcono = static function (array $item, string $fallback = 'chevron-right') {
        $nombre = (string) data_get($item, 'icono.nombre', '');

        if ($nombre !== '') {
            return $nombre;
        }

        $componente = (string) data_get($item, 'icono.componente', '');
        if ($componente !== '' && str_starts_with($componente, 'heroicon-')) {
            return (string) str($componente)->after('heroicon-o-')->after('heroicon-s-')->after('heroicon-m-');
        }

        return $fallback;
    };
@endphp

<div>
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
        <div class="flex h-[74px] items-center px-6 shadow-sm">
            <h1 class="w-full text-center text-[17px] font-semibold leading-tight tracking-wide whitespace-nowrap text-neutral-900">DIRECTORIO TELEFONICO</h1>
        </div>

        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="text-[14px] text-neutral-800">
                <li>
                    <a href="{{ $baseUrl }}" class="flex items-center gap-2 px-4 py-3 shadow-[inset_0_-1px_0_0_rgba(0,0,0,0.12)] transition hover:bg-neutral-200">
                        <x-ui.sidebar-icon name="home" class="h-5 w-5 shrink-0" />
                        <span>Inicio</span>
                    </a>
                </li>

                @if (! empty($menus))
                    @foreach ($menus as $menu)
                        @php
                            $submenus = is_array($menu['submenus'] ?? null) ? $menu['submenus'] : [];
                            $menuNombre = (string) ($menu['nombre'] ?? 'Menú');
                            $menuRuta = $resolverRuta((string) ($menu['ruta'] ?? ''), $baseUrl);
                            $menuIcono = $resolverIcono($menu, 'squares-2x2');
                        @endphp

                        @if (empty($submenus))
                            <li>
                                <a href="{{ $menuRuta }}" class="flex items-center gap-2 px-4 py-3 shadow-[inset_0_-1px_0_0_rgba(0,0,0,0.12)] transition hover:bg-neutral-200">
                                    <x-ui.sidebar-icon :name="$menuIcono" class="h-5 w-5 shrink-0" />
                                    <span>{{ $menuNombre }}</span>
                                </a>
                            </li>
                        @else
                            <li x-data="{ abierto: false }">
                                <button
                                    type="button"
                                    class="flex w-full items-center justify-between px-4 py-3 text-left shadow-[inset_0_-1px_0_0_rgba(0,0,0,0.12)] transition hover:bg-neutral-200"
                                    @click="abierto = !abierto"
                                >
                                    <span class="flex items-center gap-2">
                                        <x-ui.sidebar-icon :name="$menuIcono" class="h-5 w-5 shrink-0" />
                                        <span>{{ $menuNombre }}</span>
                                    </span>
                                    <span class="text-sm" x-text="abierto ? '▾' : '▸'"></span>
                                </button>

                                <ul x-show="abierto" x-collapse>
                                    @foreach ($submenus as $submenu)
                                        @php
                                            $modulos = is_array($submenu['modulos'] ?? null) ? $submenu['modulos'] : [];
                                            $submenuNombre = (string) ($submenu['nombre'] ?? 'Submenú');
                                            $submenuRuta = $resolverRuta((string) ($submenu['ruta'] ?? ''), $baseUrl);
                                            $submenuIcono = $resolverIcono($submenu, 'list-bullet');
                                        @endphp

                                        @if (empty($modulos))
                                            <li>
                                                <a href="{{ $submenuRuta }}" class="flex items-center gap-2 py-2.5 pr-4 pl-8 text-[14px] shadow-[inset_0_-1px_0_0_rgba(0,0,0,0.12)] transition hover:bg-neutral-200">
                                                    <x-ui.sidebar-icon :name="$submenuIcono" class="h-4 w-4 shrink-0" />
                                                    <span>{{ $submenuNombre }}</span>
                                                </a>
                                            </li>
                                        @else
                                            <li x-data="{ abierto: false }">
                                                <button
                                                    type="button"
                                                    class="flex w-full items-center justify-between py-2.5 pr-4 pl-8 text-left text-[14px] shadow-[inset_0_-1px_0_0_rgba(0,0,0,0.12)] transition hover:bg-neutral-200"
                                                    @click="abierto = !abierto"
                                                >
                                                    <span class="flex items-center gap-2">
                                                        <x-ui.sidebar-icon :name="$submenuIcono" class="h-4 w-4 shrink-0" />
                                                        <span>{{ $submenuNombre }}</span>
                                                    </span>
                                                    <span class="text-xs" x-text="abierto ? '▾' : '▸'"></span>
                                                </button>

                                                <ul x-show="abierto" x-collapse>
                                                    @foreach ($modulos as $modulo)
                                                        @php
                                                            $moduloNombre = (string) ($modulo['nombre'] ?? 'Módulo');
                                                            $moduloRuta = $resolverRuta((string) ($modulo['ruta'] ?? ''), $baseUrl);
                                                            $moduloIcono = $resolverIcono($modulo, 'chevron-right');
                                                        @endphp
                                                        <li>
                                                            <a href="{{ $moduloRuta }}" class="flex items-center gap-2 py-2 pr-4 pl-12 text-[14px] shadow-[inset_0_-1px_0_0_rgba(0,0,0,0.12)] transition hover:bg-neutral-200">
                                                                <x-ui.sidebar-icon :name="$moduloIcono" class="h-4 w-4 shrink-0" />
                                                                <span>{{ $moduloNombre }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                @endif

                <li>
                    <a href="#" class="flex items-center gap-2 px-4 py-3 shadow-[inset_0_-1px_0_0_rgba(0,0,0,0.12)] transition hover:bg-neutral-200">
                        <x-ui.sidebar-icon name="arrow-right-on-rectangle" class="h-5 w-5 shrink-0" />
                        <span>Cerrar Sesión</span>
                    </a>
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
