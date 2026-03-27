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

        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

        <style type="text/tailwindcss">
            @theme {
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/axios@1.13.4/dist/axios.min.js"></script>

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
        @stack('css')

    </head>

    <body
        class="min-h-screen bg-neutral-100 antialiased"
        x-data="{
            sidebarOpen: false,
            setupSidebar() {
                const desktopMedia = window.matchMedia('(min-width: 1024px)');
                this.sidebarOpen = desktopMedia.matches;
                desktopMedia.addEventListener('change', (event) => {
                    this.sidebarOpen = event.matches;
                });
            },
            toggleSidebar() {
                if (window.matchMedia('(min-width: 1024px)').matches) {
                    this.sidebarOpen = !this.sidebarOpen;
                    return;
                }

                this.sidebarOpen = !this.sidebarOpen;
            }
        }"
        x-init="setupSidebar()"
    >

        <div class="relative min-h-screen">
            @include('layouts.sidebar')

            <div
                class="flex min-h-screen flex-col transition-[margin] duration-300 lg:ml-0"
                :class="sidebarOpen ? 'lg:ml-72' : 'lg:ml-0'"
            >
                {{-- =============================================
                     HEADER / NAVEGACIÓN
                ============================================== --}}
                @include('layouts.header')

                {{-- =============================================
                     CONTENIDO PRINCIPAL
                ============================================== --}}
                <main class="flex-1">
                    @yield('alerts')
                    @yield('content')
                </main>

                <footer>
                    @yield('footer')
                </footer>
            </div>
        </div>

        @include('layouts.modal')

        <script>
            window.AppRoutes = Object.freeze({
                home: @json(route('home')),
                login: @json(route('login')),
                ingresar: @json(route('ingresar')),
                authGoogle: @json(route('auth.google')),
                authGoogleStatus: @json(route('auth.google.status')),
                maestrosPaises: @json(route('maestros.paises')),
            });
        </script>
        <script src="{{ asset('js/dialog.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/dates.js') }}"></script>
        <script src="{{ asset('js/functions.js') }}"></script>
        <script src="{{ asset('js/strings.js') }}"></script>

        @yield('scripts')
        @stack('js')

    </body>

</html>
