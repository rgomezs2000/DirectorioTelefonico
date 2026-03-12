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
            ALPINE.JS v3.15.8
            Siempre al final del <head> con defer
        ============================================== --}}
        <script defer
                src="https://cdn.jsdelivr.net/npm/[email protected]/dist/cdn.min.js">
        </script>

        {{-- =============================================
             CSS GLOBAL (compilado de la app)
        ============================================== --}}
        {{-- @vite(['resources/css/app.css']) --}}
        {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}

        {{-- CSS extra inyectado desde la vista hija --}}
        @yield('styles')

        {{-- Stack para hojas de estilo adicionales --}}
        @stack('css')

    </head>

    <body>

        {{-- =============================================
             HEADER / NAVEGACIÓN
        ============================================== --}}
        <header>
            @yield('header')
        </header>

        {{-- =============================================
             BARRA LATERAL (opcional)
        ============================================== --}}
        @hasSection('sidebar')
            <aside>
                @yield('sidebar')
            </aside>
        @endif

        {{-- =============================================
             CONTENIDO PRINCIPAL
        ============================================== --}}
        <main>

            {{-- Breadcrumb opcional --}}
            @yield('breadcrumb')

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

        {{-- =============================================
             MODALES GLOBALES (portales)
        ============================================== --}}
        @include('layouts.modal')

        {{-- =============================================
             JS GLOBAL (compilado de la app)
        ============================================== --}}
        @vite(['resources/js/app.js'])
        {{-- <script src="{{ asset('js/app.js') }}"></script> --}}

        {{-- JS extra inyectado desde la vista hija --}}
        @yield('scripts')

        {{-- Stack para scripts adicionales --}}
        @stack('js')

    </body>

</html>