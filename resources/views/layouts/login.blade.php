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
             SEO — sobreescribible desde la vista hija
        ============================================== --}}
        <title>@yield('title', 'Acceso — ' . config('app.name'))</title>
        <meta name="description" content="@yield('meta_description', '')">
        <meta name="robots"      content="noindex, nofollow">

        {{-- =============================================
             FAVICON
        ============================================== --}}
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        {{-- =============================================
             TAILWIND v4  +  CSS compilado (Vite)
             Descomenta la línea que uses:
        ============================================== --}}
        {{-- @vite(['resources/css/app.css']) --}}
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

        {{-- CSS EXTRA inyectado desde la vista hija --}}
        @yield('styles')

        {{-- Stack para <link> o <style> adicionales --}}
        @stack('css')

        {{-- =============================================
             AXIOS — carga ANTES de Alpine
        ============================================== --}}
        <script src="https://cdn.jsdelivr.net/npm/axios@1.13.4/dist/axios.min.js"></script>

        {{-- Token CSRF global para todas las peticiones Axios --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                axios.defaults.headers.common['X-CSRF-TOKEN'] =
                    document.querySelector('meta[name="csrf-token"]')?.content ?? '';
            });
        </script>

        {{-- =============================================
             ALPINE.JS — defer obligatorio, al final del head
        ============================================== --}}
        <script defer src="https://cdn.jsdelivr.net/npm/[email protected]/dist/cdn.min.js"></script>

    </head>

    {{-- x-data en body para que Alpine esté disponible globalmente --}}
    <body class="antialiased bg-neutral-100 min-h-screen" x-data>

        {{-- =============================================
             CONTENIDO — único slot de este layout.
             Sin header, sidebar ni footer.
             Exclusivo para pantallas de autenticación.
        ============================================== --}}
        <main>
            @yield('content')
        </main>

        {{-- =============================================
             MODALES / OVERLAYS globales
        ============================================== --}}
        @yield('modals')

        {{-- =============================================
             JS COMPILADO (Vite) — descomenta si usas build
        ============================================== --}}
        {{-- @vite(['resources/js/app.js']) --}}

        {{-- JS extra desde la vista hija --}}
        @yield('scripts')

        {{-- Stack para scripts acumulados --}}
        @stack('js')

    </body>

</html>