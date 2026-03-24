@extends('layouts.layout')

@section('title', 'Home — Directorio Telefónico')

@section('content')
    <section class="p-4 md:p-8">
        <div class="mx-auto max-w-5xl rounded-sm bg-neutral-300 p-4 md:p-6 lg:p-8">
            <div class="h-[340px] overflow-hidden rounded-sm md:h-[520px] lg:h-[680px]">
                <img src="{{ asset('img/directorio.png') }}"
                     alt="Logo"
                     class="w-full h-full object-cover">
            </div>
        </div>
    </section>
@endsection
