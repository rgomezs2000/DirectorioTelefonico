@extends('layouts.layout')

@section('title', 'Gestionar Países')

@section('content')
    @php
        $datatableConfig = $datatableConfig ?? [
            'rows' => [],
            'columns' => ['id_pais', 'nombre', 'nombre_oficial', 'iso2', 'iso3', 'codigo_numerico', 'codigo_telefono', 'continente', 'capital', 'moneda', 'idioma_oficial', 'activo', 'creado_en'],
            'fieldOptions' => [
                ['value' => '__all__', 'label' => 'Todo'],
                ['value' => 'id_pais', 'label' => 'Id Pais'],
                ['value' => 'nombre', 'label' => 'País'],
                ['value' => 'nombre_oficial', 'label' => 'Nombre Oficial'],
                ['value' => 'iso2', 'label' => 'ISO2'],
                ['value' => 'iso3', 'label' => 'ISO3'],
                ['value' => 'codigo_numerico', 'label' => 'Código Numérico'],
                ['value' => 'codigo_telefono', 'label' => 'Código Teléfono'],
                ['value' => 'continente', 'label' => 'Continente'],
                ['value' => 'capital', 'label' => 'Capital'],
                ['value' => 'moneda', 'label' => 'Moneda'],
                ['value' => 'idioma_oficial', 'label' => 'Idioma Oficial'],
                ['value' => 'activo', 'label' => 'Activo'],
                ['value' => 'creado_en', 'label' => 'Creado En'],
            ],
        ];
    @endphp
    <section class="p-4 md:p-6 lg:p-8" x-data="datatables({
        endpoint: null,
        rows: @js($datatableConfig['rows']),
        columns: @js($datatableConfig['columns']),
        fieldOptions: @js($datatableConfig['fieldOptions']),
        searchField: '__all__'
    })" x-init="init()">
        <div class="mx-auto w-full max-w-6xl space-y-5">
            <h1 class="text-center text-2xl font-bold tracking-wide text-neutral-900 md:text-4xl">GESTIONAR PAISES</h1>

            <div class="rounded-md border border-neutral-200 bg-white p-4 shadow-sm">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <button type="button" class="inline-flex items-center gap-2 rounded-sm bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375H14.25m-4.5 0H7.875A3.375 3.375 0 0 0 4.5 11.625v6.75a3.375 3.375 0 0 0 3.375 3.375h8.25a3.375 3.375 0 0 0 3.375-3.375V16.5M16.5 3.75v4.5m2.25-2.25h-4.5" />
                        </svg>
                        Nuevo
                    </button>

                    <div>
                        <select x-model="searchField" @change="setPage(1)" class="rounded-sm border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700">
                            <template x-for="field in fieldOptions" :key="field.value">
                                <option :value="field.value" x-text="field.label"></option>
                            </template>
                        </select>
                    </div>

                    <div class="relative w-full lg:max-w-md">
                        <input
                            x-model="search"
                            @input="setPage(1)"
                            type="text"
                            placeholder="Buscar..."
                            class="w-full rounded-sm border border-neutral-200 bg-neutral-100 px-10 py-2 text-sm text-neutral-700 outline-none focus:border-blue-300 focus:bg-white"
                        >
                        <svg class="pointer-events-none absolute left-3 top-2.5 h-5 w-5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3m1.3-5.2a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/>
                        </svg>
                    </div>

                    <button type="button" class="inline-flex items-center gap-2 rounded-sm bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.83 0 5.375 1.214 7.144 3.149M12 3v3m0-3H9m3 18c-2.83 0-5.375-1.214-7.144-3.149M12 21v-3m0 3h3M6.856 6.149A9 9 0 0 0 3 12m3.856 5.851A9 9 0 0 1 3 12m18 0a9 9 0 0 1-3.856 5.851M21 12a9 9 0 0 0-3.856-5.851" />
                        </svg>
                        Búsqueda avanzada
                    </button>

                    <button type="button" @click="clearFilters()" class="inline-flex items-center gap-2 rounded-sm bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        Limpiar
                    </button>

                    <div class="lg:ml-auto">
                        <select x-model.number="perPage" @change="setPage(1)" class="rounded-sm border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700">
                            <option :value="25">25 por página</option>
                            <option :value="50">50 por página</option>
                            <option :value="100">100 por página</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="button" class="inline-flex items-center gap-2 rounded-sm bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-800">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    Eliminar todo
                </button>
            </div>

            <div class="overflow-hidden rounded-md border border-neutral-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full table-auto text-sm text-neutral-700">
                        <thead class="bg-neutral-100 text-neutral-900">
                            <tr>
                                <th class="px-3 py-3 text-left">
                                    <input type="checkbox" class="h-4 w-4 rounded border-neutral-300" :checked="allVisibleSelected" @change="toggleAll($event.target.checked)">
                                </th>
                                <th class="px-3 py-3 text-left font-semibold">
                                    <div class="flex items-center gap-2">
                                        Código
                                        <button type="button" class="text-xs text-neutral-500 hover:text-neutral-800" @click="toggleSort('iso2')">↕</button>
                                    </div>
                                </th>
                                <th class="px-3 py-3 text-left font-semibold">
                                    <div class="flex items-center gap-2">
                                        País
                                        <button type="button" class="text-xs text-neutral-500 hover:text-neutral-800" @click="toggleSort('nombre')">↕</button>
                                    </div>
                                </th>
                                <th class="px-3 py-3 text-left font-semibold">
                                    <div class="flex items-center gap-2">
                                        Idioma oficial
                                        <button type="button" class="text-xs text-neutral-500 hover:text-neutral-800" @click="toggleSort('idioma_oficial')">↕</button>
                                    </div>
                                </th>
                                <th class="px-3 py-3 text-left font-semibold">
                                    <div class="flex items-center gap-2">
                                        Estado
                                        <button type="button" class="text-xs text-neutral-500 hover:text-neutral-800" @click="toggleSort('activo')">↕</button>
                                    </div>
                                </th>
                                <th class="px-3 py-3 text-center font-semibold">Ver</th>
                                <th class="px-3 py-3 text-center font-semibold">Activar/Desactivar</th>
                                <th class="px-3 py-3 text-center font-semibold">Modificar</th>
                                <th class="px-3 py-3 text-center font-semibold">Eliminar</th>
                            </tr>
                        </thead>

                        <tbody>
                            <template x-for="row in paginatedRows" :key="row.id_pais">
                                <tr class="border-t border-neutral-200 hover:bg-neutral-50">
                                    <td class="px-3 py-3">
                                        <input type="checkbox" class="h-4 w-4 rounded border-neutral-300" :checked="isSelected(row.id_pais)" @change="toggleRow(row.id_pais)">
                                    </td>
                                    <td class="px-3 py-3" x-text="row.iso2"></td>
                                    <td class="px-3 py-3" x-text="row.nombre"></td>
                                    <td class="px-3 py-3" x-text="row.idioma_oficial"></td>
                                    <td class="px-3 py-3" x-text="row.activo ? 'Activo' : 'Inactivo'"></td>
                                    <td class="px-3 py-3 text-center"><button type="button" class="inline-flex items-center justify-center rounded-sm bg-emerald-600 px-3 py-1 text-white" aria-label="Ver">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12S5.25 6.75 12 6.75 21.75 12 21.75 12 18.75 17.25 12 17.25 2.25 12 2.25 12Z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </button></td>
                                    <td class="px-3 py-3 text-center">
                                        <button
                                            type="button"
                                            @click="row.activo = !row.activo"
                                            class="inline-flex items-center justify-center rounded-sm px-3 py-1"
                                            :class="row.activo ? 'bg-neutral-900 text-white' : 'border border-neutral-300 bg-white text-neutral-900'"
                                        >
                                            <svg x-show="!row.activo" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5a1.5 1.5 0 0 1 1.5 1.5v6.75a1.5 1.5 0 0 1-1.5 1.5H6.75a1.5 1.5 0 0 1-1.5-1.5V12a1.5 1.5 0 0 1 1.5-1.5Z" />
                                            </svg>
                                            <svg x-show="row.activo" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6.75a3.75 3.75 0 1 0-7.5 0v3.75m-1.5 0h10.5A1.5 1.5 0 0 1 18.75 12v6.75a1.5 1.5 0 0 1-1.5 1.5H6.75a1.5 1.5 0 0 1-1.5-1.5V12a1.5 1.5 0 0 1 1.5-1.5Z" />
                                            </svg>
                                        </button>
                                    </td>
                                    <td class="px-3 py-3 text-center"><button type="button" class="inline-flex items-center justify-center rounded-sm bg-amber-500 px-3 py-1 text-white" aria-label="Modificar">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487ZM18 14.25V18A2.25 2.25 0 0 1 15.75 20.25H6A2.25 2.25 0 0 1 3.75 18V8.25A2.25 2.25 0 0 1 6 6h3.75" />
                                        </svg>
                                    </button></td>
                                    <td class="px-3 py-3 text-center"><button type="button" class="inline-flex items-center justify-center rounded-sm bg-red-600 px-3 py-1 text-white" aria-label="Eliminar">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button></td>
                                </tr>
                            </template>
                            <tr x-show="paginatedRows.length === 0">
                                <td colspan="9" class="px-3 py-6 text-center text-neutral-500">No se encontraron registros.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col items-center justify-between gap-3 border-t border-neutral-200 px-4 py-3 text-sm md:flex-row">
                    <p class="text-neutral-600">Mostrando <span x-text="rangeStart"></span>-<span x-text="rangeEnd"></span> de <span x-text="filteredRows.length"></span></p>
                    <div class="flex items-center gap-1">
                        <button type="button" @click="setPage(page - 1)" :disabled="page <= 1" class="rounded px-3 py-1 text-neutral-700 hover:bg-neutral-100 disabled:opacity-40">Previous</button>
                        <template x-for="pageItem in pages" :key="pageItem">
                            <button type="button" @click="setPage(pageItem)" :class="pageItem === page ? 'bg-neutral-900 text-white' : 'text-neutral-700 hover:bg-neutral-100'" class="rounded px-3 py-1" x-text="pageItem"></button>
                        </template>
                        <button type="button" @click="setPage(page + 1)" :disabled="page >= totalPages" class="rounded px-3 py-1 text-neutral-700 hover:bg-neutral-100 disabled:opacity-40">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal_title')
    <h3 class="text-base font-semibold text-neutral-800"></h3>
@endsection

@section('modal_body')
@endsection

@section('modal_footer')
@endsection
