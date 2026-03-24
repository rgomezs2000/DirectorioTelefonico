@extends('layouts.layout')

@section('title', 'Home — Directorio Telefónico')

@section('content')
    <section class="p-4 md:p-6 lg:p-8">
        <div class="mx-auto flex w-full max-w-5xl items-center justify-center rounded-md">
            <img src="{{ asset('img/directorio.png') }}"
                 alt="Logo"
                 class="block h-auto w-full max-h-[78vh] object-contain object-center">
        </div>
    </section>
@endsection
