{{-- =============================================================
     resources/views/auth/login.blade.php
     Extiende: layouts/app.blade.php
     Vista: Login — Directorio Telefónico
============================================================= --}}

@extends('layouts.app')

{{-- ── SEO ─────────────────────────────────────────────────── --}}
@section('title', 'Iniciar sesión — Directorio Telefónico')
@section('meta_description', 'Accede al Directorio Telefónico con tus credenciales.')

{{-- ── ANIMACIÓN DE ENTRADA (puro CSS, sin JS) ────────────── --}}
@push('css')
<style>
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim-title  { animation: fadeSlideUp .45s ease both; }
    .anim-card   { animation: fadeSlideUp .45s .1s ease both; }
    .anim-row-1  { animation: fadeSlideUp .4s .15s ease both; }
    .anim-row-2  { animation: fadeSlideUp .4s .22s ease both; }
    .anim-row-3  { animation: fadeSlideUp .4s .29s ease both; }
    .anim-row-4  { animation: fadeSlideUp .4s .36s ease both; }
</style>
@endpush

{{-- ── CONTENIDO PRINCIPAL ─────────────────────────────────── --}}
@section('content')

<div class="min-h-screen bg-neutral-100 flex flex-col">

    {{-- ══════════════════════════════════════════════════════
         TÍTULO
    ══════════════════════════════════════════════════════════ --}}
    <header class="anim-title w-full pt-12 pb-8 text-center">
        <h1 class="text-3xl sm:text-4xl font-light tracking-[.25em] text-neutral-800 uppercase">
            Directorio Telefónico
        </h1>
        <div class="mx-auto mt-4 w-14 h-px bg-neutral-400"></div>
    </header>

    {{-- ══════════════════════════════════════════════════════
         CARD LOGIN
    ══════════════════════════════════════════════════════════ --}}
    <main class="flex-1 flex items-start justify-center px-4 pb-20">

        <div class="anim-card w-full max-w-2xl bg-white border border-neutral-300 shadow-lg
                    flex flex-col sm:flex-row overflow-hidden">

            {{-- ────────────────────────────────────────────
                 PANEL IZQUIERDO  —  logo / imagen
            ──────────────────────────────────────────────── --}}
            <aside class="sm:w-5/12 min-h-52 sm:min-h-0
                          bg-neutral-200 border-b sm:border-b-0 sm:border-r border-neutral-300
                          flex flex-col items-center justify-center gap-5 p-8">

                {{-- Ícono teléfono (Heroicons outline) --}}
                {{-- Reemplaza por: <img src="{{ asset('img/logo.svg') }}" alt="Logo" class="w-24"> --}}
                <div class="w-20 h-20 rounded-full bg-white border border-neutral-300 shadow-sm
                            flex items-center justify-center">
                    <svg class="w-9 h-9 text-neutral-600"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372
                                 c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417
                                 l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143
                                 c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173
                                 L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                    </svg>
                </div>

                <span class="text-xs font-medium tracking-widest text-neutral-500 uppercase text-center">
                    {{ config('app.name', 'Directorio') }}
                </span>

            </aside>

            {{-- ────────────────────────────────────────────
                 PANEL DERECHO  —  formulario
            ──────────────────────────────────────────────── --}}
            <section class="flex-1 flex flex-col">

                {{-- Errores de validación Laravel --}}
                @if ($errors->any())
                    <div class="px-6 pt-5">
                        <div class="alert alert-danger text-sm">
                            <svg class="w-4 h-4 shrink-0 mt-0.5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v3.75m0 3.75h.008v.008H12v-.008Zm9.303-9.376
                                         c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126
                                         c-.866 1.5.217 3.374 1.948 3.374h14.71
                                         c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378Z"/>
                            </svg>
                            <ul class="space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Status de sesión (logout, etc.) --}}
                @if (session('status'))
                    <div class="px-6 pt-5">
                        <div class="alert alert-success text-sm">
                            {{ session('status') }}
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" novalidate
                      class="flex-1 flex flex-col divide-y divide-neutral-200">
                    @csrf

                    {{-- ── FILA 1: LOGIN ──────────────────────────── --}}
                    <div class="anim-row-1 grid grid-cols-2 divide-x divide-neutral-200">

                        <div class="flex items-center px-6 py-5 bg-neutral-50">
                            <label for="email"
                                   class="text-sm font-bold tracking-wider text-neutral-700 uppercase">
                                Login
                            </label>
                        </div>

                        <div class="flex items-center px-5 py-4">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="correo@ejemplo.com"
                                class="form-input text-sm
                                       @error('email') border-red-400 focus:border-red-500 @enderror"
                            >
                        </div>

                    </div>{{-- /FILA 1 --}}

                    {{-- ── FILA 2: PASSWORD ───────────────────────── --}}
                    <div class="anim-row-2 grid grid-cols-2 divide-x divide-neutral-200">

                        <div class="flex items-center px-6 py-5 bg-neutral-50">
                            <label for="password"
                                   class="text-sm font-bold tracking-wider text-neutral-700 uppercase">
                                Password
                            </label>
                        </div>

                        <div class="flex items-center px-5 py-4">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="form-input text-sm
                                       @error('password') border-red-400 focus:border-red-500 @enderror"
                            >
                        </div>

                    </div>{{-- /FILA 2 --}}

                    {{-- ── FILA 3: BOTONES INGRESAR / SALIR ──────── --}}
                    <div class="anim-row-3 grid grid-cols-2 divide-x divide-neutral-200">

                        <div class="flex items-center justify-center px-6 py-5">
                            <button type="submit"
                                    class="btn btn-primary w-full text-sm">
                                Ingresar
                            </button>
                        </div>

                        <div class="flex items-center justify-center px-5 py-5">
                            <a href="{{ url('/') }}"
                               class="btn btn-outline w-full text-center text-sm">
                                Salir
                            </a>
                        </div>

                    </div>{{-- /FILA 3 --}}

                    {{-- ── FILA 4: GOOGLE ─────────────────────────── --}}
                    <div class="anim-row-4 px-8 py-5 flex justify-center">
                        <a href="{{ route('auth.google') ?? '#' }}"
                           class="btn btn-ghost w-full max-w-xs border border-neutral-300
                                  gap-3 justify-center hover:bg-neutral-50 text-sm">

                            {{-- Logo Google (colores oficiales, SVG inline) --}}
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="#4285F4"
                                      d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92
                                         c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57
                                         c2.08-1.92 3.28-4.74 3.28-8.09Z"/>
                                <path fill="#34A853"
                                      d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77
                                         c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84
                                         C3.99 20.53 7.7 23 12 23Z"/>
                                <path fill="#FBBC05"
                                      d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18
                                         C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84Z"/>
                                <path fill="#EA4335"
                                      d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15
                                         C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84
                                         c.87-2.6 3.3-4.53 6.16-4.53Z"/>
                            </svg>

                            <span>Google</span>
                        </a>
                    </div>{{-- /FILA 4 --}}

                    {{-- ── ¿Olvidaste tu contraseña? ──────────────── --}}
                    @if (Route::has('password.request'))
                        <div class="px-6 pb-5 pt-1 text-center">
                            <a href="{{ route('password.request') }}"
                               class="text-xs text-neutral-400 hover:text-neutral-600
                                      underline underline-offset-2 transition-colors">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    @endif

                </form>

            </section>
            {{-- /PANEL DERECHO --}}

        </div>
        {{-- /CARD --}}

    </main>

</div>

@endsection