<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Panel de usuarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Mensaje de éxito --}}
            @if (session('status'))
                <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Barra superior --}}
            <div class="mb-6 space-y-4">

                {{-- Contador + Crear usuario --}}
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="text-gray-900 dark:text-gray-100">
                        <strong>{{ $usersNumber }}</strong> usuario(s) registrados
                    </div>

                    <a href="{{ route('external-users.create') }}"
                        class="inline-flex items-center gap-2 px-3 py-2 rounded text-sm
                        bg-blue-600 text-white hover:bg-blue-700">
                        ➕ Crear usuario
                    </a>
                </div>

                {{-- Gestión de catálogos --}}
                <div class="bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 rounded p-4 flex flex-wrap gap-2">
                    <a href="{{ route('catalogs.show', 'dependencias') }}"
                        class="inline-flex items-center gap-2 px-3 py-2 rounded text-sm
                               bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        🏢 Dependencias
                    </a>

                    <a href="{{ route('catalogs.show', 'programas') }}"
                        class="inline-flex items-center gap-2 px-3 py-2 rounded text-sm
                               bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        📘 Programas
                    </a>

                    <a href="{{ route('catalogs.show', 'roles') }}"
                        class="inline-flex items-center gap-2 px-3 py-2 rounded text-sm
                               bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        👤 Roles
                    </a>

                    <a href="{{ route('catalogs.show', 'semestres') }}"
                        class="inline-flex items-center gap-2 px-3 py-2 rounded text-sm
                               bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        📅 Semestres
                    </a>
                </div>
            </div>

            {{-- Tabla de usuarios (igual que antes) --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-2">Usuario</th>
                                <th class="px-3 py-2">Nombre</th>
                                <th class="px-3 py-2">Apellido</th>
                                <th class="px-3 py-2">Correo</th>
                                <th class="px-3 py-2">Dependencia</th>
                                <th class="px-3 py-2">Programa</th>
                                <th class="px-3 py-2">Rol</th>
                                <th class="px-3 py-2">Semestre</th>
                                <th class="px-3 py-2 text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($users as $user)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-3 py-2 font-medium">{{ $user->username }}</td>
                                    <td class="px-3 py-2">{{ $user->firstname }}</td>
                                    <td class="px-3 py-2">{{ $user->lastname }}</td>
                                    <td class="px-3 py-2">{{ $user->email }}</td>
                                    <td class="px-3 py-2">{{ $user->dependencia }}</td>
                                    <td class="px-3 py-2">{{ $user->programa }}</td>
                                    <td class="px-3 py-2">{{ $user->rol }}</td>
                                    <td class="px-3 py-2">{{ $user->semestre }}</td>
                                    <td class="px-3 py-2 flex justify-center gap-2">
                                        <a href="{{ route('external-users.edit', $user->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-medium
                                                   bg-blue-600 text-white hover:bg-blue-700">
                                            ✏️ Editar
                                        </a>

                                        <form action="{{ route('external-users.destroy', $user->id) }}" method="POST"
                                              onsubmit="return confirm('¿Deseas eliminar este usuario?')">
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
                                    <td colspan="9" class="px-3 py-6 text-center text-gray-500">
                                        No se encontraron usuarios registrados.
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
