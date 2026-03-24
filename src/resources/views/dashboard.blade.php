<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Panel de usuarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success message --}}
            @if (session('status'))
                <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Errores de importación CSV --}}
            @if (session('import_errors') && count(session('import_errors')) > 0)
                <div class="mb-4 rounded bg-yellow-50 border border-yellow-200 px-4 py-3">
                    <p class="font-semibold text-yellow-800 mb-2">⚠️ Algunas filas no pudieron importarse:</p>
                    <ul class="list-disc list-inside text-sm text-yellow-700 space-y-1">
                        @foreach (session('import_errors') as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Top bar --}}
            <div class="mb-6 space-y-4">

                {{-- Counter + Create --}}
                <div class="flex flex-wrap items-center justify-between gap-4">

                    <div class="text-gray-900 dark:text-gray-100">
                        <strong>{{ $usersNumber }}</strong> usuario(s) registrados
                    </div>

                    <div class="flex gap-2">
                        {{-- Create External User --}}
                        <a href="{{ route('external-users.create') }}"
                           class="inline-flex items-center gap-2 px-3 py-2 rounded text-sm
                                  bg-blue-600 text-white hover:bg-blue-700">
                            ➕ Crear usuario
                        </a>

                        {{-- Import CSV --}}
                        <a href="{{ route('external-users.import.form') }}"
                           class="inline-flex items-center gap-2 px-3 py-2 rounded text-sm
                                  bg-indigo-600 text-white hover:bg-indigo-700">
                            📤 Importar CSV
                        </a>

                        {{-- Superadmin: Manage Admin Users --}}
                        @auth
                            @if(auth()->user()->role === 'superadmin')
                                <a href="{{ route('admins.index') }}"
                                   class="inline-flex items-center gap-2 px-3 py-2 rounded text-sm
                                          bg-green-600 text-white hover:bg-green-700">
                                    👑 Administrar Admins
                                </a>
                            @endif
                        @endauth
                    </div>

                </div>

                {{-- Catalog management --}}
                <div class="bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 rounded p-4 flex flex-wrap gap-2">
                    <a href="{{ route('catalogs.show', 'dependencias') }}" class="catalog-btn">🏢 Dependencias</a>
                    <a href="{{ route('catalogs.show', 'programas') }}" class="catalog-btn">📘 Programas</a>
                    <a href="{{ route('catalogs.show', 'roles') }}" class="catalog-btn">👤 Roles</a>
                    <a href="{{ route('catalogs.show', 'semestres') }}" class="catalog-btn">📅 Semestres</a>
                </div>

            </div>

            {{-- Users table --}}
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

                                        <form method="POST"
                                              action="{{ route('external-users.destroy', $user->id) }}"
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

            {{-- Paginación --}}
            @if ($users->hasPages())
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
