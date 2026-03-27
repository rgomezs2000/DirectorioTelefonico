@extends('layouts.layout')

@section('title', 'Gestionar Países')

@section('content')
    <section class="p-4 md:p-6 lg:p-8" x-data="datatables({
        endpoint: null,
        rows: [
            { id: 1, codigo: 'PE', nombre: 'Perú', gentilicio: 'Peruano', estado: 'Activo' },
            { id: 2, codigo: 'CL', nombre: 'Chile', gentilicio: 'Chileno', estado: 'Activo' },
            { id: 3, codigo: 'AR', nombre: 'Argentina', gentilicio: 'Argentino', estado: 'Activo' },
            { id: 4, codigo: 'CO', nombre: 'Colombia', gentilicio: 'Colombiano', estado: 'Activo' },
            { id: 5, codigo: 'EC', nombre: 'Ecuador', gentilicio: 'Ecuatoriano', estado: 'Inactivo' }
        ],
        columns: ['codigo', 'nombre', 'gentilicio', 'estado']
    })" x-init="init()">
        <div class="mx-auto w-full max-w-6xl space-y-5">
            <h1 class="text-center text-2xl font-bold tracking-wide text-neutral-900 md:text-4xl">GESTIONAR PAISES</h1>

            <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <button type="button" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Nuevo</button>

                    <div class="relative w-full lg:max-w-lg">
                        <input
                            x-model="search"
                            @input="setPage(1)"
                            type="text"
                            placeholder="Buscar..."
                            class="w-full rounded-lg border border-neutral-200 bg-neutral-100 px-10 py-2 text-sm text-neutral-700 outline-none focus:border-blue-300 focus:bg-white"
                        >
                        <svg class="pointer-events-none absolute left-3 top-2.5 h-5 w-5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3m1.3-5.2a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/>
                        </svg>
                    </div>

                    <button type="button" class="inline-flex items-center gap-2 rounded-sm bg-emerald-600 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.83 0 5.375 1.214 7.144 3.149M12 3v3m0-3H9m3 18c-2.83 0-5.375-1.214-7.144-3.149M12 21v-3m0 3h3M6.856 6.149A9 9 0 0 0 3 12m3.856 5.851A9 9 0 0 1 3 12m18 0a9 9 0 0 1-3.856 5.851M21 12a9 9 0 0 0-3.856-5.851" />
                        </svg>
                        Búsqueda avanzada
                    </button>

                    <button type="button" class="inline-flex items-center gap-2 rounded-sm border border-neutral-300 bg-white px-3 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346-.346a2.25 2.25 0 0 0-3.182 0L9 10.864m0 0L6.818 8.682a2.25 2.25 0 0 0-3.182 0L3 9.318m6 1.546 2.182 2.182a2.25 2.25 0 0 0 3.182 0L17 10.864m-8 0v8.25A2.25 2.25 0 0 0 11.25 21h1.5A2.25 2.25 0 0 0 15 18.75v-7.886" />
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

            <div class="overflow-hidden rounded-md border border-neutral-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-neutral-700">
                        <thead class="bg-neutral-100 text-neutral-900">
                            <tr>
                                <th class="w-12 px-3 py-3 text-left">
                                    <input type="checkbox" class="h-4 w-4 rounded border-neutral-300" :checked="allVisibleSelected" @change="toggleAll($event.target.checked)">
                                </th>
                                <th class="px-3 py-3 text-left font-semibold">
                                    <div class="flex items-center gap-2">
                                        Código
                                        <button type="button" class="text-xs text-neutral-500 hover:text-neutral-800" @click="toggleSort('codigo')">↕</button>
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
                                        Gentilicio
                                        <button type="button" class="text-xs text-neutral-500 hover:text-neutral-800" @click="toggleSort('gentilicio')">↕</button>
                                    </div>
                                </th>
                                <th class="px-3 py-3 text-left font-semibold">
                                    <div class="flex items-center gap-2">
                                        Estado
                                        <button type="button" class="text-xs text-neutral-500 hover:text-neutral-800" @click="toggleSort('estado')">↕</button>
                                    </div>
                                </th>
                                <th class="px-3 py-3 text-center font-semibold">Ver</th>
                                <th class="px-3 py-3 text-center font-semibold">Modificar</th>
                                <th class="px-3 py-3 text-center font-semibold">Eliminar</th>
                            </tr>
                        </thead>

                        <tbody>
                            <template x-for="row in paginatedRows" :key="row.id">
                                <tr class="border-t border-neutral-200 hover:bg-neutral-50">
                                    <td class="px-3 py-3">
                                        <input type="checkbox" class="h-4 w-4 rounded border-neutral-300" :checked="isSelected(row.id)" @change="toggleRow(row.id)">
                                    </td>
                                    <td class="px-3 py-3" x-text="row.codigo"></td>
                                    <td class="px-3 py-3" x-text="row.nombre"></td>
                                    <td class="px-3 py-3" x-text="row.gentilicio"></td>
                                    <td class="px-3 py-3" x-text="row.estado"></td>
                                    <td class="px-3 py-3 text-center"><button type="button" class="rounded bg-emerald-600 px-3 py-1 text-xs font-semibold text-white">Ver</button></td>
                                    <td class="px-3 py-3 text-center"><button type="button" class="rounded bg-amber-500 px-3 py-1 text-xs font-semibold text-white">Editar</button></td>
                                    <td class="px-3 py-3 text-center"><button type="button" class="rounded bg-red-600 px-3 py-1 text-xs font-semibold text-white">Eliminar</button></td>
                                </tr>
                            </template>
                            <tr x-show="paginatedRows.length === 0">
                                <td colspan="8" class="px-3 py-6 text-center text-neutral-500">No se encontraron registros.</td>
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
