@props([
    'name' => 'squares-2x2',
    'class' => 'h-5 w-5 shrink-0',
])

@php
    $icono = trim((string) $name);
@endphp

@if ($icono === 'home')
    <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12 11.2 3.85a1.2 1.2 0 0 1 1.6 0L21.75 12M4.5 9.9V20.25h5.25V15a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75v5.25h5.25V9.9" />
    </svg>
@elseif ($icono === 'arrow-right-on-rectangle')
    <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.625A2.625 2.625 0 0 0 13.125 3h-6.75A2.625 2.625 0 0 0 3.75 5.625v12.75A2.625 2.625 0 0 0 6.375 21h6.75a2.625 2.625 0 0 0 2.625-2.625V15" />
        <path stroke-linecap="round" stroke-linejoin="round" d="m12.75 12h8.25m0 0-2.625-2.625M21 12l-2.625 2.625" />
    </svg>
@elseif ($icono === 'list-bullet')
    <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12M8.25 17.25h12" />
        <circle cx="4.125" cy="6.75" r=".9" fill="currentColor" />
        <circle cx="4.125" cy="12" r=".9" fill="currentColor" />
        <circle cx="4.125" cy="17.25" r=".9" fill="currentColor" />
    </svg>
@elseif ($icono === 'chevron-right')
    <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="m9 5.25 6 6-6 6" />
    </svg>
@else
    <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h7.5v6h-7.5zm9 0h7.5v6h-7.5zm-9 7.5h7.5v6h-7.5zm9 0h7.5v6h-7.5z" />
    </svg>
@endif
