<x-app-layout>
    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Panel de usuarios
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-800 w-full">
        <div class="w-full max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-gray-100 dark:bg-gray-800 rounded-[30px] shadow-xl overflow-hidden w-full">
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
                            <div class="text-gray-900 dark:text-gray-100 bg-gray-400 dark:bg-gray-800 px-3 py-1 rounded-full text-sm">
                                <strong>{{ $usersNumber }}</strong> usuario(s) registrados
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('external-users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white shadow-md hover:bg-blue-700 transition">
                                    ➕ Crear usuario
                                </a>
                                <a href="{{ route('external-users.import.form') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-gray-500 text-white shadow-md hover:bg-indigo-700 transition">
                                    📤 Importar CSV
                                </a>
                                <a href="{{ route('register-config.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-gray-500 text-white shadow-md hover:bg-amber-600 transition">
                                    ⚙️ Configurar registro
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

                        <div class="bg-gray-100 dark:bg-gray-900 rounded-xl p-4 flex flex-wrap gap-3 border border-gray-200 dark:border-gray-700">
                            <a href="{{ route('catalogs.show', 'dependencias') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-900 dark:bg-gray-800 dark:text-gray-100 hover:bg-emerald-700 hover:text-white shadow transition">
                                🏢 Dependencias
                            </a>
                            <a href="{{ route('catalogs.show', 'programas') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-900 dark:bg-gray-800 dark:text-gray-100 hover:bg-sky-700 hover:text-white shadow transition">
                                📘 Programas
                            </a>
                            <a href="{{ route('catalogs.show', 'roles') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-900 dark:bg-gray-800 dark:text-gray-100 hover:bg-amber-700 hover:text-white shadow transition">
                                👤 Roles
                            </a>
                            <a href="{{ route('catalogs.show', 'semestres') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-900 dark:bg-gray-800 dark:text-gray-100 hover:bg-purple-700 hover:text-white shadow transition">
                                📅 Semestres
                            </a>
                        </div>
                    </div>

                    <section class="space-y-6">
                        {{-- FILTROS DESPLEGABLES --}}
                        @php
                            $activeFilters = collect([
                                request('from_date'), request('to_date'),
                                request('search'), request('curp'),
                                request('dependencia'), request('programa'),
                                request('rol'), request('semestre'),
                            ])->filter()->count();

                            // Validación de rango de fechas para mostrar aviso
                            $fromDate = request('from_date');
                            $toDate   = request('to_date');
                            $dateError = ($fromDate && $toDate && $fromDate > $toDate);
                        @endphp

                        <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

                            {{-- Cabecera siempre visible --}}
                            <button type="button" id="toggle-filters"
                                class="w-full flex items-center justify-between px-5 py-3 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-left">
                                <span class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    🔎 Filtros
                                    @if($activeFilters > 0)
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-600 text-white text-xs font-bold">{{ $activeFilters }}</span>
                                    @endif
                                </span>
                                <span class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                                    <span>Total: <strong class="text-gray-800 dark:text-gray-100">{{ $usersNumber }}</strong> usuario(s)</span>
                                    <svg id="filter-chevron" class="w-4 h-4 transition-transform duration-300 {{ $activeFilters > 0 ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </span>
                            </button>

                            {{-- Panel colapsable --}}
                            <div id="filters-panel" class="{{ $activeFilters > 0 ? '' : 'hidden' }} bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-5 py-5">

                                @if($dateError)
                                    <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 px-4 py-2 text-sm text-red-700 dark:text-red-300">
                                        ⚠️ La fecha <strong>Desde</strong> no puede ser mayor que la fecha <strong>Hasta</strong>.
                                    </div>
                                @endif

                                <form action="{{ route('dashboard') }}" method="GET" id="filters-form">

                                    {{-- Fila 1: Búsqueda general + CURP --}}
                                    <div class="grid gap-4 sm:grid-cols-2 mb-4">
                                        <div>
                                            <label for="search" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Buscar por nombre / usuario / email</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                                                </span>
                                                <input type="text" name="search" id="search"
                                                    placeholder="Juan, jperez, juan@mail.com…"
                                                    value="{{ request('search') }}"
                                                    class="pl-9 block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                            </div>
                                        </div>
                                        <div>
                                            <label for="curp" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">CURP</label>
                                            <input type="text" name="curp" id="curp"
                                                placeholder="XXXX000000XXXXXX00"
                                                value="{{ request('curp') }}"
                                                maxlength="18"
                                                style="text-transform:uppercase"
                                                class="block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 font-mono">
                                        </div>
                                    </div>

                                    {{-- Fila 2: Rango de fechas --}}
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Rango de fecha de registro</label>
                                        <div class="flex items-center gap-2">
                                            <input type="date" name="from_date" id="from_date"
                                                value="{{ request('from_date') }}"
                                                max="{{ date('Y-m-d') }}"
                                                onchange="validateDateRange()"
                                                class="block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 {{ $dateError ? 'border-red-400' : '' }}">
                                            <span class="text-gray-400 text-sm flex-shrink-0">→</span>
                                            <input type="date" name="to_date" id="to_date"
                                                value="{{ request('to_date') }}"
                                                max="{{ date('Y-m-d') }}"
                                                onchange="validateDateRange()"
                                                class="block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 {{ $dateError ? 'border-red-400' : '' }}">
                                            {{-- Accesos rápidos --}}
                                            <div class="flex gap-1 flex-shrink-0">
                                                <button type="button" onclick="setDateRange('today')" title="Hoy"
                                                    class="px-2 py-1.5 text-xs rounded bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-100 dark:hover:bg-blue-900 transition">
                                                    Hoy
                                                </button>
                                                <button type="button" onclick="setDateRange('week')" title="Última semana"
                                                    class="px-2 py-1.5 text-xs rounded bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-100 dark:hover:bg-blue-900 transition">
                                                    7d
                                                </button>
                                                <button type="button" onclick="setDateRange('month')" title="Último mes"
                                                    class="px-2 py-1.5 text-xs rounded bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-100 dark:hover:bg-blue-900 transition">
                                                    30d
                                                </button>
                                            </div>
                                        </div>
                                        <p id="date-range-error" class="text-xs text-red-500 mt-1 {{ $dateError ? '' : 'hidden' }}">
                                            La fecha inicial no puede ser mayor que la final.
                                        </p>
                                    </div>

                                    {{-- Fila 3: Selects de catálogos --}}
                                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-4">
                                        <div>
                                            <label for="dependencia" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Dependencia</label>
                                            <select id="dependencia" name="dependencia" class="block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                                <option value="">— Todas —</option>
                                                @foreach($dependencias ?? [] as $id => $nombre)
                                                    <option value="{{ $id }}" {{ request('dependencia') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="programa" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Programa</label>
                                            <select id="programa" name="programa" class="block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                                <option value="">— Todos —</option>
                                                @foreach($programas ?? [] as $id => $nombre)
                                                    <option value="{{ $id }}" {{ request('programa') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="rol" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Rol</label>
                                            <select id="rol" name="rol" class="block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                                <option value="">— Todos —</option>
                                                @foreach($roles ?? [] as $id => $nombre)
                                                    <option value="{{ $id }}" {{ request('rol') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="semestre" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Semestre</label>
                                            <select id="semestre" name="semestre" class="block w-full rounded border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                                <option value="">— Todos —</option>
                                                @foreach($semestres ?? [] as $id => $nombre)
                                                    <option value="{{ $id }}" {{ request('semestre') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Botones --}}
                                    <div class="flex flex-wrap gap-2 items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                                        <button type="submit" id="btn-filtrar"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition shadow-sm">
                                            🔎 Aplicar filtros
                                        </button>
                                        <a href="{{ route('dashboard') }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition">
                                            ✖ Limpiar filtros
                                        </a>
                                        @if($activeFilters > 0)
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">
                                                {{ $activeFilters }} filtro(s) activo(s)
                                            </span>
                                        @endif
                                    </div>
                                </form>
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

                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 w-full">
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                                    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-900 text-gray-300 dark:text-gray-200">
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
                                            <tr data-user-row="{{ $user->id }}" class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800/70 transition">
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
                                                    <button type="button"
                                                        data-delete-id="{{ $user->id }}"
                                                        data-delete-url="{{ route('external-users.destroy', $user->id) }}"
                                                        data-delete-token="{{ csrf_token() }}"
                                                        onclick="deleteUserRow(this)"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium bg-red-600 text-white hover:bg-red-700 shadow-sm transition">
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

                        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 bg-gray-100 dark:bg-gray-900 p-4 rounded-xl border border-gray-200 dark:border-gray-900">
                            <button type="button" id="bulk-delete-btn"
                                class="inline-flex items-center gap-2 rounded bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 shadow-sm transition">
                                🗑 Eliminar seleccionados
                            </button>
                            <button type="button" onclick="window.location.replace(window.location.origin + window.location.pathname);" class="inline-flex items-center justify-center rounded border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-medium text-gray-900 hover:border-gray-400 hover:bg-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:border-gray-500 dark:hover:bg-gray-600 transition w-full sm:w-auto">
                                     Actualizar Tabla
                            </button>
                            
                            @if (method_exists($users, 'hasPages') && $users->hasPages())
                                <div>
                                    {{ $users->links() }}
                                </div>
                            @endif
                        </div>
                    </form>
                    </section>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ── Select-all checkbox ──────────────────────────────────────
            const selectAll = document.querySelector('#select_all');
            const rows = document.querySelectorAll('input.select-row[type="checkbox"]');

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    rows.forEach(cb => cb.checked = selectAll.checked);
                });
            }

            // ── Eliminación bulk en tiempo real ─────────────────────────
            const bulkBtn = document.getElementById('bulk-delete-btn');
            if (bulkBtn) {
                bulkBtn.addEventListener('click', async function () {
                    const checked = [...document.querySelectorAll('input.select-row:checked')];
                    if (checked.length === 0) {
                        alert('Selecciona al menos un usuario.');
                        return;
                    }
                    if (!confirm(`¿Deseas eliminar ${checked.length} usuario(s) seleccionado(s)?`)) return;

                    bulkBtn.disabled = true;
                    bulkBtn.textContent = '⏳ Eliminando...';

                    const ids   = checked.map(cb => cb.value);
                    const token = '{{ csrf_token() }}';
                    const url   = '{{ route("external-users.bulk.destroy") }}';

                    const body = new URLSearchParams();
                    body.append('_token', token);
                    ids.forEach(id => body.append('selected_users[]', id));

                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                            body,
                        });

                        if (res.ok) {
                            checked.forEach(cb => {
                                const row = cb.closest('tr[data-user-row]');
                                if (row) {
                                    row.style.transition = 'opacity 0.3s, transform 0.3s';
                                    row.style.opacity    = '0';
                                    row.style.transform  = 'translateX(20px)';
                                    setTimeout(() => row.remove(), 300);
                                }
                            });
                            const selectAll = document.getElementById('select_all');
                            if (selectAll) selectAll.checked = false;
                        } else {
                            const err = await res.json().catch(() => ({}));
                            alert(err.message ?? 'No se pudieron eliminar los usuarios.');
                        }
                    } catch (e) {
                        alert('Error de red al intentar eliminar.');
                    } finally {
                        bulkBtn.disabled = false;
                        bulkBtn.innerHTML = '🗑 Eliminar seleccionados';
                    }
                });
            }

            // ── Filtros desplegables ─────────────────────────────────────
            const toggleBtn   = document.getElementById('toggle-filters');
            const filtersPanel = document.getElementById('filters-panel');
            const chevron     = document.getElementById('filter-chevron');

            if (toggleBtn && filtersPanel) {
                toggleBtn.addEventListener('click', function () {
                    const isHidden = filtersPanel.classList.toggle('hidden');
                    chevron.classList.toggle('rotate-180', !isHidden);
                });
            }
        });

        // ── Eliminación en tiempo real (individual) ──────────────────────
        async function deleteUserRow(btn) {
            if (!confirm('¿Deseas eliminar este usuario?')) return;

            const userId  = btn.dataset.deleteId;
            const url     = btn.dataset.deleteUrl;
            const token   = btn.dataset.deleteToken;

            btn.disabled = true;
            btn.textContent = '⏳';

            try {
                const res = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                });

                if (res.ok || res.status === 204 || res.status === 302) {
                    const row = document.querySelector(`tr[data-user-row="${userId}"]`);
                    if (row) {
                        row.style.transition = 'opacity 0.3s, transform 0.3s';
                        row.style.opacity    = '0';
                        row.style.transform  = 'translateX(20px)';
                        setTimeout(() => row.remove(), 300);
                    }
                } else {
                    alert('No se pudo eliminar el usuario. Intenta de nuevo.');
                    btn.disabled = false;
                    btn.innerHTML = '🗑 Eliminar';
                }
            } catch (e) {
                alert('Error de red al intentar eliminar.');
                btn.disabled = false;
                btn.innerHTML = '🗑 Eliminar';
            }
        }
    </script>
</x-app-layout>