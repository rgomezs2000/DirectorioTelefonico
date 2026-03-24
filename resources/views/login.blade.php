{{-- =============================================================
     resources/views/login.blade.php
     Extiende: layouts/login.blade.php
     Vista: Login — Directorio Telefónico
     Librerías: Tailwind v4 · Alpine.js · Axios
============================================================= --}}

@extends('layouts.login')

{{-- ── SEO ─────────────────────────────────────────────────── --}}
@section('title', 'Iniciar sesión — Directorio Telefónico')
@section('meta_description', 'Accede al Directorio Telefónico con tus credenciales.')

{{-- ── ESTILOS: animaciones de entrada (CSS puro) ─────────── --}}
@push('css')
<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0);    }
    }
    .au-0 { animation: fadeUp .5s ease both; }
    .au-1 { animation: fadeUp .5s .10s ease both; }
    .au-2 { animation: fadeUp .4s .18s ease both; }
    .au-3 { animation: fadeUp .4s .26s ease both; }
    .au-4 { animation: fadeUp .4s .34s ease both; }
    .au-5 { animation: fadeUp .4s .42s ease both; }
</style>
@endpush

{{-- ════════════════════════════════════════════════════════════
     CONTENT
═══════════════════════════════════════════════════════════════ --}}
@section('content')

<div class="min-h-screen bg-neutral-100 flex flex-col">

    {{-- ══════════════════════════════════════════════════════
         TÍTULO SUPERIOR
    ══════════════════════════════════════════════════════════ --}}
    <header class="au-0 w-full pt-12 pb-8 text-center select-none">
        <h1 class="text-3xl sm:text-4xl font-light tracking-[.3em] uppercase text-neutral-800">
            Directorio Telefónico
        </h1>
        <div class="mx-auto mt-4 h-px w-16 bg-neutral-400"></div>
    </header>

    {{-- ══════════════════════════════════════════════════════
         CARD  —  Alpine.js controla el estado del formulario
    ══════════════════════════════════════════════════════════ --}}
    <main class="flex-1 flex items-start justify-center px-2 sm:px-3 md:px-4 lg:px-6 2xl:px-8 pb-20">

        {{-- ─────────────────────────────────────────────────
             x-data: estado local del componente login
             · loading  → muestra spinner mientras Axios trabaja
             · error    → mensaje de error genérico de red
             · form     → campos del formulario
        ────────────────────────────────────────────────────── --}}
        <div class="au-1 w-full max-w-[98vw] sm:max-w-[95vw] md:max-w-[92vw] lg:max-w-5xl xl:max-w-6xl 2xl:max-w-7xl bg-white shadow-lg
                    flex flex-col overflow-hidden"
             x-data="{
                 loading : false,
                 form    : { login: '', password: '' },
                 googleLoading: false,
                 googleLoggedIn: false,
                 googleUser: null,
                 showModal(type, title, message) {
                     const store = window.Alpine?.store?.('dialog');
                     if (store && typeof window.dialog === 'function') {
                         store.show(window.dialog(type, title, message));
                     }
                 }
             }"
             x-init="
                 window.googleClientId = '{{ config('services.google.client_id') }}';

                 const validationErrors = @js($errors->all());
                 if (validationErrors.length) {
                     showModal('error', 'No fue posible iniciar sesión', validationErrors.join('\n'));
                 }

                 const sessionStatus = @js(session('status'));
                 if (sessionStatus) {
                     showModal('info', 'Información', sessionStatus);
                 }

                 const authStatus = await window.fetchGoogleSessionStatus();
                 googleLoggedIn = !!authStatus?.is_logged_in;
                 googleUser = authStatus?.user ?? null;
             ">

            <div class="au-2 w-full py-4 text-center bg-white">
                <h2 class="text-base sm:text-lg font-semibold tracking-[.25em] uppercase text-neutral-700">Iniciar Sesión</h2>
            </div>

            <div class="flex flex-col sm:flex-row bg-white">
            {{-- ════════════════════════════════════════════
                 PANEL IZQUIERDO  —  logo / imagen
            ═══════════════════════════════════════════════ --}}
            <aside class="sm:w-5/12 lg:w-[42%] min-h-52 sm:min-h-0 bg-white overflow-hidden">
                <img src="{{ asset('img/directorio.png') }}"
                         alt="Logo"
                         class="w-full h-full object-cover">
                
                
                <!-- {{-- Logo / imagen ubicada en /public/img/logo-login.png --}}
                <div class="w-20 h-20 rounded-full bg-white border border-neutral-300 shadow-sm
                            flex items-center justify-center">
                    <img src="{{ asset('img/directorio.png') }}"
                         alt="Logo"
                         class="w-14 h-14 object-contain">
                </div>

                <span class="text-xs font-medium tracking-widest text-neutral-500 uppercase text-center">
                    {{ config('app.name', 'Directorio') }}
                </span> -->

            </aside>

            {{-- ════════════════════════════════════════════
                 PANEL DERECHO  —  formulario
            ═══════════════════════════════════════════════ --}}
            <section class="flex-1 flex flex-col bg-white">

                {{-- ────────────────────────────────────────────
                     FORMULARIO — submit interceptado por Alpine
                     @submit.prevent evita recarga de página
                ──────────────────────────────────────────────── --}}
                <form @submit.prevent="window.loginAjax($data)"
                      novalidate
                      class="flex-1 flex flex-col">

                    @csrf

                    {{-- ── FILA 1: LOGIN ─────────────────────── --}}
                    <div class="au-2 grid grid-cols-2">

                        <div class="flex items-center px-6 py-5 bg-white">
                            <label for="login"
                                   class="text-sm font-bold tracking-wider uppercase text-neutral-700">
                                Login
                            </label>
                        </div>

                        <div class="flex items-center px-5 py-4">
                            <input
                                id="login"
                                type="text"
                                name="login"
                                x-model="form.login"
                                value="{{ old('login') }}"
                                required
                                autofocus
                                autocomplete="off"
                                placeholder="usuario"
                                :disabled="loading"
                                class="w-full rounded border border-neutral-300 px-3 py-2 text-sm
                                       text-neutral-800 placeholder-neutral-400
                                       focus:outline-none focus:border-neutral-500 focus:ring-2
                                       focus:ring-neutral-200 transition disabled:opacity-50
                                       @error('login') border-red-400 @enderror"
                            >
                        </div>

                    </div>{{-- /FILA 1 --}}

                    {{-- ── FILA 2: PASSWORD ───────────────────── --}}
                    <div class="au-3 grid grid-cols-2">

                        <div class="flex items-center px-6 py-5 bg-white">
                            <label for="password"
                                   class="text-sm font-bold tracking-wider uppercase text-neutral-700">
                                Password
                            </label>
                        </div>

                        <div class="flex items-center px-5 py-4">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                x-model="form.password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                :disabled="loading"
                                class="w-full rounded border border-neutral-300 px-3 py-2 text-sm
                                       text-neutral-800 placeholder-neutral-400
                                       focus:outline-none focus:border-neutral-500 focus:ring-2
                                       focus:ring-neutral-200 transition disabled:opacity-50
                                       @error('password') border-red-400 @enderror"
                            >
                        </div>

                    </div>{{-- /FILA 2 --}}

                    {{-- ── FILA 3: INGRESAR / SALIR ───────────── --}}
                    <div class="au-4 grid grid-cols-2">

                        {{-- Botón INGRESAR — muestra spinner mientras carga --}}
                        <div class="flex items-center justify-center px-6 py-5">
                            <button type="submit"
                                    :disabled="loading"
                                    class="w-full flex items-center justify-center gap-2
                                           rounded border border-neutral-800 bg-neutral-800
                                           px-4 py-2 text-sm font-semibold text-white
                                           hover:bg-neutral-700 transition disabled:opacity-60
                                           disabled:cursor-not-allowed">

                                {{-- Spinner Alpine (visible solo cuando loading = true) --}}
                                <svg x-show="loading"
                                     x-cloak
                                     class="w-4 h-4 animate-spin text-white"
                                     fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8v8H4Z"/>
                                </svg>

                                <span x-text="loading ? 'Ingresando…' : 'Ingresar'">Ingresar</span>
                            </button>
                        </div>

                        {{-- Botón SALIR / Cancelar --}}
                        <div class="flex items-center justify-center px-5 py-5">
                            <a href="{{ url('/') }}"
                               class="w-full flex items-center justify-center
                                      rounded border border-neutral-400 bg-white
                                      px-4 py-2 text-sm font-semibold text-neutral-700
                                      hover:bg-neutral-100 transition text-center">
                                Salir
                            </a>
                        </div>

                    </div>{{-- /FILA 3 --}}

                    {{-- ── FILA 4: GOOGLE ──────────────────────── --}}
                    <div class="au-5 px-5 sm:px-8 py-5 flex justify-center">
                        <div id="g_id_onload"
                             data-client_id="{{ config('services.google.client_id') }}"
                             data-callback="handleCredentialResponse"
                             data-auto_prompt="false"
                             data-login_uri="{{ route('auth.google') }}">
                        </div>
                        <div class="g_id_signin"
                             data-type="standard"
                             data-size="large"
                             data-theme="outline"
                             data-shape="rectangular"
                             data-text="signin_with">
                        </div>
                    </div>{{-- /FILA 4 --}}

                    <div class="px-5 sm:px-8 pb-6 text-center text-xs text-neutral-500"
                         x-show="googleUser"
                         x-cloak>
                        <p>
                            Estado Google:
                            <span class="font-semibold" x-text="googleLoggedIn ? 'Sesión iniciada' : 'Sin sesión'"></span>
                        </p>
                        <p x-text="googleUser?.email ?? ''"></p>
                    </div>

                    {{-- ── ¿Olvidaste tu contraseña? ──────────── --}}
                    @if (Route::has('password.request'))
                        <div class="px-6 pb-5 pt-1 text-center">
                            <a href="{{ route('password.request') }}"
                               class="text-xs text-neutral-400 hover:text-neutral-600
                                      underline underline-offset-2 transition-colors">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    @endif

                </form>{{-- /form --}}

            </section>
            </div>
            {{-- /PANEL DERECHO --}}

        </div>
        {{-- /CARD Alpine --}}

    </main>

</div>

@endsection
