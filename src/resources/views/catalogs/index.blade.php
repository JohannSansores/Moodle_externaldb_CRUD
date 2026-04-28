<x-app-layout>
    {{-- Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gestión de {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success message --}}
            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Add new item --}}
            <div class="mb-6 flex flex-wrap items-center gap-4">
                <form action="{{ route('catalogs.store') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="text" name="nombre" placeholder="Nuevo elemento"
                           class="px-2 py-1 border rounded text-sm w-40 dark:bg-gray-700 dark:text-gray-200"
                           required>
                    <button type="submit"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-medium
                               bg-blue-600 text-white hover:bg-blue-700">
                        ➕ Crear
                    </button>
                </form>

                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-medium
                           bg-gray-200 text-gray-800 hover:bg-gray-300
                           dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600
                           border border-gray-300 dark:border-gray-600">
                    🔙 Volver
                </a>
            </div>

            {{-- Table of items --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-2">Nombre</th>
                                <th class="px-3 py-2 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-3 py-2">{{ $item->nombre }}</td>
                                    <td class="px-3 py-2 text-center flex justify-center gap-2">

                                        {{-- Save (Edit) --}}
                                        <form action="{{ route('catalogs.update', [$type, $item->id]) }}" method="POST" class="flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="nombre" value="{{ $item->nombre }}"
                                                   class="px-2 py-1 border rounded text-sm w-40 dark:bg-gray-700 dark:text-gray-200" required>
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-medium
                                                       bg-blue-600 text-white hover:bg-blue-700">
                                                💾 Guardar
                                            </button>
                                        </form>

                                        {{-- Delete --}}
                                        <form action="{{ route('catalogs.destroy', [$type, $item->id]) }}" method="POST"
                                              onsubmit="return confirm('¿Deseas eliminar este elemento?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-medium
                                                       bg-red-600 text-white hover:bg-red-700">
                                                🗑 Eliminar
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-3 py-4 text-center text-gray-500">
                                        No hay elementos en este catálogo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
