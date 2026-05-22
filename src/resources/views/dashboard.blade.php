<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Panel de usuarios
        </h2>
    </x-slot>

    <div class="py-12 bg-white dark:bg-gray-800 w-full">
        <div class="w-full">
            <!-- Contenedor sólido (sin transparencias) -->
            <div class="bg-white dark:bg-gray-800 rounded-[30px] shadow-xl overflow-hidden w-full">
                <div class="px-6 py-8 sm:px-10">

                    <!-- Mensajes de estado -->
                    @if (session('status'))
                        <div class="mb-4 rounded-lg bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-4 py-3 border border-green-200 dark:border-green-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('import_errors') && count(session('import_errors')) > 0)
                        <div class="mb-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 px-4 py-3">
                            <p class="font-semibold text-yellow-800 dark:text-yellow-300 mb-2">⚠️ Algunas filas no pudieron importarse:</p>
                            <ul class="list-disc list-inside text-sm text-yellow-700 dark:text-yellow-400 space-y-1">
                                @foreach (session('import_errors') as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Barra superior -->
                    <div class="mb-6 space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full text-sm">
                                <strong>{{ $usersNumber }}</strong> usuario(s) registrados
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('external-users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-black dark:text-white shadow-md hover:bg-blue-700 transition">
                                    ➕ Crear usuario
                                </a>
                                <a href="{{ route('external-users.import.form') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-indigo-600 text-black dark:text-white shadow-md hover:bg-indigo-700 transition">
                                    📤 Importar CSV
                                </a>
                                @if(auth()->user()->role === 'superadmin')
                                    <a href="{{ route('admins.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-green-600 text-black dark:text-white shadow-md hover:bg-green-700 transition">
                                        👑 Administrar Admins
                                    </a>
                                    <a href="{{ route('database.export') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-gray-700 text-black dark:text-white shadow-md hover:bg-gray-800 transition">
                                        🗄️ Exportar estructura BD
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Catálogos con colores sólidos -->
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-4 flex flex-wrap gap-3 border border-gray-200 dark:border-gray-600">
                            <a href="{{ route('catalogs.show', 'dependencias') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-emerald-600 text-dark-gray hover:bg-emerald-700 shadow transition">🏢 Dependencias</a>
                            <a href="{{ route('catalogs.show', 'programas') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-sky-600 text-dark-gray hover:bg-sky-700 shadow transition">📘 Programas</a>
                            <a href="{{ route('catalogs.show', 'roles') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-amber-600 text-dark-gray hover:bg-amber-700 shadow transition">👤 Roles</a>
                            <a href="{{ route('catalogs.show', 'semestres') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-purple-600 text-dark-gray hover:bg-purple-700 shadow transition">📅 Semestres</a>
                        </div>
                    </div>

                    <!-- Tabla sólida -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 w-full">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                                <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200">
                                    <tr>
                                        <th class="px-4 py-3">Usuario</th>
                                        <th class="px-4 py-3">Nombre</th>
                                        <th class="px-4 py-3">Apellido</th>
                                        <th class="px-4 py-3">Correo</th>
                                        <th class="px-4 py-3">CURP</th>
                                        <th class="px-4 py-3">Dependencia</th>
                                        <th class="px-4 py-3">Programa</th>
                                        <th class="px-4 py-3">Rol</th>
                                        <th class="px-4 py-3">Semestre</th>
                                        <th class="px-4 py-3 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/70 transition">
                                            <td class="px-4 py-2 font-medium">{{ $user->username }}</td>
                                            <td class="px-4 py-2">{{ $user->firstname }}</td>
                                            <td class="px-4 py-2">{{ $user->lastname }}</td>
                                            <td class="px-4 py-2">{{ $user->email }}</td>
                                            <td class="px-4 py-2 font-mono text-xs">{{ $user->curp ?? '—' }}</td>
                                            <td class="px-4 py-2">{{ $user->id_dependencia ?? '—' }}</td>
                                            <td class="px-4 py-2">{{ $user->id_programa ?? '—' }}</td>
                                            <td class="px-4 py-2">{{ $user->id_rol ?? '—' }}</td>
                                            <td class="px-4 py-2">{{ $user->id_semestre ?? '—' }}</td>
                                            <td class="px-4 py-2 flex justify-center gap-2">
                                                <a href="{{ route('external-users.edit', $user->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium bg-blue-600 text-white hover:bg-blue-700 shadow-sm transition">
                                                    ✏️ Editar
                                                </a>
                                                <form method="POST" action="{{ route('external-users.destroy', $user->id) }}" onsubmit="return confirm('¿Deseas eliminar este usuario?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium bg-red-600 text-white hover:bg-red-700 shadow-sm transition">
                                                        🗑 Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                                No se encontraron usuarios registrados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paginación -->
                    @if (method_exists($users, 'hasPages') && $users->hasPages())
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif

                </div>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>