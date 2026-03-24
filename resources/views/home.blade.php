@extends('layouts.layout')

@section('title', 'Home — Directorio Telefónico')

@section('content')
    <section class="p-4 md:p-6 lg:p-8">
        <div class="mx-auto h-[360px] w-full max-w-5xl overflow-hidden rounded-md shadow-sm md:h-[560px] lg:h-[760px]">
            <img src="{{ asset('img/directorio.png') }}"
                 alt="Logo"
                 class="w-full h-full object-cover">
        </div>
    </section>
@endsection
