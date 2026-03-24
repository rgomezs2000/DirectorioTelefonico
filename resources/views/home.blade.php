@extends('layouts.layout')

@section('title', 'Home — Directorio Telefónico')

@section('content')
    <section class="flex min-h-[calc(100vh-9rem)] items-center justify-center p-4 md:min-h-[calc(100vh-10rem)] md:p-6 lg:p-8">
        <div class="mx-auto flex w-full max-w-5xl items-center justify-center rounded-md">
            <img src="{{ asset('img/directorio.png') }}"
                 alt="Logo"
                 class="block h-auto w-full max-h-[70vh] rounded-lg object-contain object-center">
        </div>
    </section>
@endsection
