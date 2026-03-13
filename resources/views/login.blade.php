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
        <div class="au-1 w-full max-w-[98vw] sm:max-w-[95vw] md:max-w-[92vw] lg:max-w-5xl xl:max-w-6xl 2xl:max-w-7xl bg-white border border-neutral-300 shadow-lg
                    flex flex-col sm:flex-row overflow-hidden"
             x-data="{
                 loading : false,
                 netError: '',
                 form    : { email: '', password: '' },

                 async submitLogin() {
                     this.loading  = true;
                     this.netError = '';
                     try {
                         const res = await axios.post('{{ route('ingresar') }}', {
                             email   : this.form.email,
                             password: this.form.password,
                             _token  : '{{ csrf_token() }}'
                         });
                         {{-- Redirección tras login exitoso --}}
                         window.location.href = res.data?.redirect ?? '{{ route('home') }}';
                     } catch (err) {
                         this.netError = err.response?.data?.message
                                      ?? 'Error de conexión. Intenta de nuevo.';
                     } finally {
                         this.loading = false;
                     }
                 }
             }">

            {{-- ════════════════════════════════════════════
                 PANEL IZQUIERDO  —  logo / imagen
            ═══════════════════════════════════════════════ --}}
            <aside class="sm:w-5/12 lg:w-[42%] min-h-52 sm:min-h-0
                          bg-neutral-200 border-b sm:border-b-0 sm:border-r border-neutral-300
                          flex flex-col items-center justify-center gap-5 p-4 sm:p-6 lg:p-8">
                <img src="{{ asset('img/directorio.png') }}"
                         alt="Logo"
                         class="w-full h-auto max-w-[320px] max-h-[280px] object-contain">
                
                
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
            <section class="flex-1 flex flex-col">

                {{-- ── Error de validación Laravel (server-side) ── --}}
                @if ($errors->any())
                    <div class="px-5 pt-5">
                        <div class="flex items-start gap-2 rounded-lg border border-red-200
                                    bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{-- Heroicon: exclamation-triangle --}}
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v3.75m0 3.75h.008v-.008H12v.008Zm9.303-9.376
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

                {{-- ── Error de red Axios (client-side Alpine) ──── --}}
                <div x-show="netError"
                     x-transition
                     class="px-5 pt-5"
                     x-cloak>
                    <div class="flex items-start gap-2 rounded-lg border border-red-200
                                bg-red-50 px-4 py-3 text-sm text-red-700">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v3.75m0 3.75h.008v-.008H12v.008Zm9.303-9.376
                                     c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126
                                     c-.866 1.5.217 3.374 1.948 3.374h14.71
                                     c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378Z"/>
                        </svg>
                        <span x-text="netError"></span>
                    </div>
                </div>

                {{-- ── Status de sesión (logout exitoso, etc.) ──── --}}
                @if (session('status'))
                    <div class="px-5 pt-5">
                        <div class="flex items-center gap-2 rounded-lg border border-green-200
                                    bg-green-50 px-4 py-3 text-sm text-green-700">
                            {{ session('status') }}
                        </div>
                    </div>
                @endif

                {{-- ────────────────────────────────────────────
                     FORMULARIO — submit interceptado por Alpine
                     @submit.prevent evita recarga de página
                ──────────────────────────────────────────────── --}}
                <form @submit.prevent="submitLogin"
                      novalidate
                      class="flex-1 flex flex-col divide-y divide-neutral-200">

                    @csrf

                    {{-- ── FILA 1: LOGIN / EMAIL ──────────────── --}}
                    <div class="au-2 grid grid-cols-2 divide-x divide-neutral-200">

                        <div class="flex items-center px-6 py-5 bg-neutral-50">
                            <label for="email"
                                   class="text-sm font-bold tracking-wider uppercase text-neutral-700">
                                Login
                            </label>
                        </div>

                        <div class="flex items-center px-5 py-4">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                x-model="form.email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="correo@ejemplo.com"
                                :disabled="loading"
                                class="w-full rounded border border-neutral-300 px-3 py-2 text-sm
                                       text-neutral-800 placeholder-neutral-400
                                       focus:outline-none focus:border-neutral-500 focus:ring-2
                                       focus:ring-neutral-200 transition disabled:opacity-50
                                       @error('email') border-red-400 @enderror"
                            >
                        </div>

                    </div>{{-- /FILA 1 --}}

                    {{-- ── FILA 2: PASSWORD ───────────────────── --}}
                    <div class="au-3 grid grid-cols-2 divide-x divide-neutral-200">

                        <div class="flex items-center px-6 py-5 bg-neutral-50">
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
                    <div class="au-4 grid grid-cols-2 divide-x divide-neutral-200">

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
                    <div class="au-5 px-8 py-5 flex justify-center">
                        <button type="button"
                                disabled
                                aria-disabled="true"
                                class="flex w-full max-w-xs items-center justify-center gap-3
                                       rounded border border-neutral-300 bg-neutral-100 px-4 py-2.5
                                       text-sm font-medium text-neutral-400 cursor-not-allowed">

                            {{-- Logo Google (SVG colores oficiales) --}}
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="#4285F4"
                                      d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92
                                         c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57
                                         c2.08-1.92 3.28-4.74 3.28-8.09Z"/>
                                <path fill="#34A853"
                                      d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77
                                         c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53
                                         H2.18v2.84C3.99 20.53 7.7 23 12 23Z"/>
                                <path fill="#FBBC05"
                                      d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09
                                         V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84Z"/>
                                <path fill="#EA4335"
                                      d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15
                                         C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84
                                         c.87-2.6 3.3-4.53 6.16-4.53Z"/>
                            </svg>

                            <span>Google</span>
                        </button>
                    </div>{{-- /FILA 4 --}}

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
            {{-- /PANEL DERECHO --}}

        </div>
        {{-- /CARD Alpine --}}

    </main>

</div>

@endsection
