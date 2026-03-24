<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        {{-- =============================================
             META BÁSICOS
        ============================================== --}}
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- =============================================
             SEO / META DINÁMICOS
        ============================================== --}}
        <title>@yield('title', config('app.name'))</title>
        <meta name="description" content="@yield('meta_description', '')">
        <meta name="keywords"    content="@yield('meta_keywords', '')">
        <meta name="author"      content="@yield('meta_author', '')">
        <meta name="robots"      content="@yield('meta_robots', 'index, follow')">

        {{-- =============================================
             OPEN GRAPH
        ============================================== --}}
        <meta property="og:title"       content="@yield('og_title', config('app.name'))">
        <meta property="og:description" content="@yield('og_description', '')">
        <meta property="og:image"       content="@yield('og_image', '')">
        <meta property="og:url"         content="@yield('og_url', request()->url())">
        <meta property="og:type"        content="@yield('og_type', 'website')">

        {{-- =============================================
             TWITTER CARD
        ============================================== --}}
        <meta name="twitter:card"        content="@yield('twitter_card', 'summary')">
        <meta name="twitter:title"       content="@yield('twitter_title', config('app.name'))">
        <meta name="twitter:description" content="@yield('twitter_description', '')">
        <meta name="twitter:image"       content="@yield('twitter_image', '')">

        {{-- =============================================
             FAVICON
        ============================================== --}}
        <link rel="icon"             type="image/x-icon" href="@yield('favicon', asset('favicon.ico'))">
        <link rel="apple-touch-icon"                     href="@yield('apple_icon', asset('apple-touch-icon.png'))">

        {{-- =============================================
            TAILWIND CSS v4  —  Play CDN
            ⚠️  Solo para desarrollo/prototipo.
            En producción usa Vite (@vite).
        ============================================== --}}
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

        {{-- Estilos globales / tokens personalizados --}}
        <style type="text/tailwindcss">
            @theme {
                /* --color-primary: #1e40af; */
                /* --font-sans: 'Inter', sans-serif; */
            }
        </style>

        {{-- =============================================
            AXIOS v1.13.4
            Debe cargarse ANTES de Alpine.js
        ============================================== --}}
        <script src="https://cdn.jsdelivr.net/npm/axios@1.13.4/dist/axios.min.js"></script>

        {{-- =============================================
             ALPINE.JS — defer obligatorio, al final del head
             Alpine Plugins + Alpine Core por CDN
        ============================================== --}}
        <script
            defer
            src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"
        ></script>
        <script
            defer
            src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
        ></script>
        <script src="https://accounts.google.com/gsi/client" async defer></script>

        {{-- CSS extra inyectado desde la vista hija --}}
        @yield('styles')

        {{-- Stack para hojas de estilo adicionales --}}
        @stack('css')

    </head>

    <body
        class="min-h-screen bg-neutral-100"
        x-data="{
            sidebarOpen: false,
            setupSidebar() {
                const media = window.matchMedia('(min-width: 1024px)');
                this.sidebarOpen = media.matches;
                media.addEventListener('change', (event) => {
                    this.sidebarOpen = event.matches;
                });
            }
        }"
        x-init="setupSidebar()"
    >

        <div class="relative min-h-screen lg:grid lg:grid-cols-[18rem_minmax(0,1fr)]">
            @include('layouts.sidebar')

            <div class="flex min-h-screen flex-col">
                {{-- =============================================
                     HEADER / NAVEGACIÓN
                ============================================== --}}
                @include('layouts.header')

                {{-- =============================================
                     CONTENIDO PRINCIPAL
                ============================================== --}}
                <main class="flex-1">
                    {{-- Alertas / flash messages --}}
                    @yield('alerts')

                    {{-- Cuerpo de la vista hija --}}
                    @yield('content')
                </main>

                {{-- =============================================
                     FOOTER
                ============================================== --}}
                <footer>
                    @yield('footer')
                </footer>
            </div>
        </div>

        {{-- =============================================
             MODALES GLOBALES (portales)
        ============================================== --}}
        @include('layouts.modal')

        {{-- =============================================
             JS GLOBAL (sin Vite)
             Lógica de modal y helpers globales vía Blade.
        ============================================== --}}
        <script>
            window.AppRoutes = Object.freeze({
                home: @json(route('home')),
                login: @json(route('login')),
                ingresar: @json(route('ingresar')),
                authGoogle: @json(route('auth.google')),
                authGoogleStatus: @json(route('auth.google.status')),
            });
        </script>
        <script src="{{ asset('js/dialog.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/dates.js') }}"></script>
        <script src="{{ asset('js/functions.js') }}"></script>
        <script src="{{ asset('js/strings.js') }}"></script>

        {{-- JS extra inyectado desde la vista hija --}}
        @yield('scripts')

        {{-- Stack para scripts adicionales --}}
        @stack('js')

    </body>

</html>
