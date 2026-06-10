<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Panel de usuarios
        </h2>
    </x-slot>

    <div class="py-12 bg-white dark:bg-gray-800 w-full">
        <div class="w-full max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 rounded-[30px] shadow-xl overflow-hidden w-full">
                <div class="px-6 py-8 sm:px-10">

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

                    <div class="mb-6 space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full text-sm">
                                <strong>{{ $usersNumber }}</strong> usuario(s) registrados
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('external-users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white shadow-md hover:bg-blue-700 transition">
                                    ➕ Crear usuario
                                </a>
                                <a href="{{ route('external-users.import.form') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-gray-500 text-white shadow-md hover:bg-indigo-700 transition">
                                    📤 Importar CSV
                                </a>
                                @if(auth()->user()->role === 'superadmin')
                                    <a href="{{ route('admins.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-green-600 text-white shadow-md hover:bg-green-700 transition">
                                        👑 Administrar Admins
                                    </a>
                                    <a href="{{ route('database.export') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-gray-500 text-white shadow-md hover:bg-gray-800 transition">
                                        🗄️ Exportar estructura BD
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-4 flex flex-wrap gap-3 border border-gray-200 dark:border-gray-600">
                            <a href="{{ route('catalogs.show', 'dependencias') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-white text-black dark:bg-gray-200 dark:text-gray-800 hover:bg-emerald-700 hover:text-white shadow transition">
                                🏢 Dependencias
                            </a>
                            <a href="{{ route('catalogs.show', 'programas') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-white text-black dark:bg-gray-200 dark:text-gray-800 hover:bg-sky-700 hover:text-white shadow transition">
                                📘 Programas
                            </a>
                            <a href="{{ route('catalogs.show', 'roles') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-white text-black dark:bg-gray-200 dark:text-gray-800 hover:bg-amber-700 hover:text-white shadow transition">
                                👤 Roles
                            </a>
                            <a href="{{ route('catalogs.show', 'semestres') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-white text-black dark:bg-gray-200 dark:text-gray-800 hover:bg-purple-700 hover:text-white shadow transition">
                                📅 Semestres
                            </a>
                        </div>
                    </div>

                    <div class="mb-6 grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] items-end bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-600">
                        <form action="{{ route('dashboard') }}" method="GET" class="grid gap-4 sm:grid-cols-3 lg:grid-cols-4 items-end w-full">
                            <div>
                                <label for="from_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Desde</label>
                                <input type="date" name="from_date" id="from_date"
                                       value="{{ request('from_date', $fromDate ?? '') }}"
                                       class="mt-1 block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            </div>

                            <div>
                                <label for="to_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Hasta</label>
                                <input type="date" name="to_date" id="to_date"
                                       value="{{ request('to_date', $toDate ?? '') }}"
                                       class="mt-1 block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            </div>

                            <div>
                                <label for="curp" class="block text-sm font-medium text-gray-700 dark:text-gray-200">CURP</label>
                                <input type="text" name="curp" id="curp" placeholder="Buscar CURP"
                                       value="{{ request('curp') }}"
                                       class="mt-1 block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 font-mono">
                            </div>

                            <div>
                                <label for="dependencia" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Dependencia</label>
                                <select id="dependencia" name="dependencia" class="mt-1 block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                    <option value="">— Todos —</option>
                                    @foreach($dependencias ?? [] as $id => $nombre)
                                        <option value="{{ $id }}" {{ request('dependencia') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="programa" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Programa</label>
                                <select id="programa" name="programa" class="mt-1 block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                    <option value="">— Todos —</option>
                                    @foreach($programas ?? [] as $id => $nombre)
                                        <option value="{{ $id }}" {{ request('programa') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="rol" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Rol</label>
                                <select id="rol" name="rol" class="mt-1 block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                    <option value="">— Todos —</option>
                                    @foreach($roles ?? [] as $id => $nombre)
                                        <option value="{{ $id }}" {{ request('rol') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="semestre" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Semestre</label>
                                <select id="semestre" name="semestre" class="mt-1 block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                    <option value="">— Todos —</option>
                                    @foreach($semestres ?? [] as $id => $nombre)
                                        <option value="{{ $id }}" {{ request('semestre') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="sm:col-span-3 lg:col-span-4 flex flex-wrap gap-2 items-center mt-2">
                                <button type="submit" class="inline-flex items-center justify-center rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 w-full sm:w-auto">
                                    🔎 Filtrar
                                </button>
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded bg-gray-200 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 w-full sm:w-auto">
                                    ♻️ Limpiar
                                </a>
                            </div>
                        </form>
                        <div class="flex items-center justify-end gap-2 text-sm text-gray-600 dark:text-gray-300">
                            <span>Total filtrado: <strong>{{ $usersNumber }}</strong></span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('external-users.bulk.destroy') }}" onsubmit="return confirm('¿Deseas eliminar los usuarios seleccionados?');">
                        @csrf
                        <input type="hidden" name="from_date" value="{{ request('from_date', $fromDate ?? '') }}">
                        <input type="hidden" name="to_date" value="{{ request('to_date', $toDate ?? '') }}">
                        <input type="hidden" name="curp" value="{{ request('curp', '') }}">
                        <input type="hidden" name="dependencia" value="{{ request('dependencia', '') }}">
                        <input type="hidden" name="programa" value="{{ request('programa', '') }}">
                        <input type="hidden" name="rol" value="{{ request('rol', '') }}">
                        <input type="hidden" name="semestre" value="{{ request('semestre', '') }}">

                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 w-full">
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                                    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200">
                                        <tr>
                                            <th class="px-4 py-3 w-10">
                                                <label class="inline-flex items-center">
                                                    <input id="select_all" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                </label>
                                            </th>
                                            <th class="px-4 py-3">Usuario</th>
                                            <th class="px-4 py-3">Nombre</th>
                                            <th class="px-4 py-3">Apellido</th>
                                            <th class="px-4 py-3">Correo</th>
                                            <th class="px-4 py-3">CURP</th>
                                            <th class="px-4 py-3">Dependencia</th>
                                            <th class="px-4 py-3">Programa</th>
                                            <th class="px-4 py-3">Rol</th>
                                            <th class="px-4 py-3">Semestre</th>
                                            <th class="px-4 py-3">Fecha de creación</th>
                                            <th class="px-4 py-3 text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $user)
                                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/70 transition">
                                                <td class="px-4 py-2">
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox" name="selected_users[]" value="{{ $user->id }}" class="select-row h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                    </label>
                                                </td>
                                                <td class="px-4 py-2 font-medium">{{ $user->username }}</td>
                                                <td class="px-4 py-2">{{ $user->firstname }}</td>
                                                <td class="px-4 py-2">{{ $user->lastname }}</td>
                                                <td class="px-4 py-2">{{ $user->email }}</td>
                                                <td class="px-4 py-2 font-mono text-xs">{{ $user->curp ?? '—' }}</td>
                                                <td class="px-4 py-2">{{ $user->dependencia ?? ($user->id_dependencia ?? '—') }}</td>
                                                <td class="px-4 py-2">{{ $user->programa ?? ($user->id_programa ?? '—') }}</td>
                                                <td class="px-4 py-2">{{ $user->rol ?? ($user->id_rol ?? '—') }}</td>
                                                <td class="px-4 py-2">{{ $user->semestre ?? ($user->id_semestre ?? '—') }}</td>
                                                <td class="px-4 py-2 text-xs">
                                                    {{ $user->created_at ? \Illuminate\Support\Carbon::parse($user->created_at)->format('d/m/Y H:i') : '—' }}
                                                </td>
                                                <td class="px-4 py-2 flex justify-center gap-2">
                                                    <a href="{{ route('external-users.edit', $user->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium bg-blue-600 text-white hover:bg-blue-700 shadow-sm transition">
                                                        ✏️ Editar
                                                    </a>
                                                    <button type="button" onclick="if(confirm('¿Deseas eliminar este usuario?')) { document.getElementById('delete-form-{{ $user->id }}').submit(); }" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium bg-red-600 text-white hover:bg-red-700 shadow-sm transition">
                                                        🗑 Eliminar
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                                    No se encontraron usuarios registrados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                            <button type="submit" class="inline-flex items-center gap-2 rounded bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 shadow-sm transition">
                                🗑 Eliminar seleccionados
                            </button>
                            
                            @if (method_exists($users, 'hasPages') && $users->hasPages())
                                <div>
                                    {{ $users->links() }}
                                </div>
                            @endif
                        </div>
                    </form>

                    @foreach ($users as $user)
                        <form id="delete-form-{{ $user->id }}" method="POST" action="{{ route('external-users.destroy', $user->id) }}" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach

                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.querySelector('#select_all');
            const rows = document.querySelectorAll('input.select-row[type="checkbox"]');

            if (!selectAll) return;

            selectAll.addEventListener('change', function () {
                rows.forEach(function (checkbox) {
                    checkbox.checked = selectAll.checked;
                });
            });
        });
    </script>
</x-app-layout>