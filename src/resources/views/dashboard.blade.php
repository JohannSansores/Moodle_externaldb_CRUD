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

                <div class="flex flex-wrap items-center justify-between gap-4">

                    <div class="text-gray-900 dark:text-gray-100">
                        <strong>{{ $usersNumber }}</strong> usuario(s) registrados
                    </div>

                    <div class="flex flex-wrap gap-2">

                        <a href="{{ route('external-users.create') }}"
                           class="inline-flex items-center gap-2 px-3 py-2 rounded text-sm
                                  bg-blue-600 text-white hover:bg-blue-700">
                            ➕ Crear usuario
                        </a>

                        <div class="py-12" style="background-image: url({{ asset('images/edificio_central.jpg') }}); background-size: cover; background-position: center; background-attachment: fixed;">
                            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                                <!-- Logo centrado y responsivo -->
                                <div class="w-full flex justify-center mb-6">
                                    <div class="flex items-center justify-center p-2">
                                        <img src="{{ asset('images/logo_uady.svg') }}" alt="Logo UADY" class="h-20 w-auto dark:hidden object-contain drop-shadow-lg" />
                                        <img src="{{ asset('images/logo-uady-blanco.png') }}" alt="Logo UADY blanco" class="hidden h-20 w-auto dark:block object-contain drop-shadow-lg" />
                                    </div>
                                </div>

                                <div class="bg-white/90 dark:bg-gray-800/80 backdrop-blur-sm rounded-lg p-6 shadow-sm">

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

                                        <div class="flex flex-wrap items-center justify-between gap-4">

                                            <div class="text-gray-900 dark:text-gray-100">
                                                <strong>{{ $usersNumber }}</strong> usuario(s) registrados
                                            </div>

                                            <div class="flex flex-wrap gap-2">

                                                <a href="{{ route('external-users.create') }}"
                                                   class="inline-flex items-center gap-2 px-3 py-2 rounded text-sm
                                                          bg-blue-600 text-white hover:bg-blue-700">
                                                    ➕ Crear usuario
                                                </a>

                                                <a href="{{ route('external-users.import.form') }}"
                                                   class="inline-flex items-center gap-2 px-3 py-2 rounded text-sm
                                                          bg-indigo-600 text-white hover:bg-indigo-700">
                                                    📤 Importar CSV
                                                </a>

                                                @if(auth()->user()->role === 'superadmin')
                                                    <a href="{{ route('admins.index') }}"
                                                       class="inline-flex items-center gap-2 px-3 py-2 rounded text-sm
                                                              bg-green-600 text-white hover:bg-green-700">
                                                        👑 Administrar Admins
                                                    </a>

                                                    <a href="{{ route('database.export') }}"
                                                       class="inline-flex items-center gap-2 px-3 py-2 rounded text-sm
                                                              bg-gray-700 text-white hover:bg-gray-800">
                                                        🗄️ Exportar estructura BD
                                                    </a>
                                                @endif

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
                                                        <th class="px-3 py-2">CURP</th>
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
                                                            <td class="px-3 py-2 font-mono text-xs">{{ $user->curp ?? '—' }}</td>
                                                            <td class="px-3 py-2">{{ $user->id_dependencia ?? '—' }}</td>
                                                            <td class="px-3 py-2">{{ $user->id_programa ?? '—' }}</td>
                                                            <td class="px-3 py-2">{{ $user->id_rol ?? '—' }}</td>
                                                            <td class="px-3 py-2">{{ $user->id_semestre ?? '—' }}</td>
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
                                                            <td colspan="10" class="px-3 py-6 text-center text-gray-500">
                                                                No se encontraron usuarios registrados.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- Paginación --}}
                                    @if (method_exists($users, 'hasPages') && $users->hasPages())
                                        <div class="mt-4">
                                            {{ $users->links() }}
                                        </div>
                                    @endif

                                </div>

                                <!-- Footer compacto (mismo estilo que guest) -->
                                <div class="mt-6">
                                    <footer style="
                                        background:#002e5f;
                                        border-radius:30px;
                                        padding:20px 30px 20px;
                                        color:white;
                                        width:100%;
                                        box-sizing:border-box;
                                        font-family:Arial,sans-serif;
                                        overflow:hidden;
                                    ">
                                        <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:20px;">
                                            <div style="flex:0 0 200px;text-align:center;">
                                                <img src="{{ asset('images/logo-uady-blanco.png') }}" alt="UADY" style="width:100%;max-width:180px;height:auto;">
                                            </div>
                                            <div style="flex:1;min-width:280px;text-align:center;">
                                                <p style="margin:0 0 12px 0;font-size:13px;line-height:1.6;color:white;font-weight:500;">Esta página puede ser reproducida con fines no lucrativos, siempre y cuando no se mutile, se cite la fuente completa y su dirección electrónica.</p>
                                                <div style="font-size:13px;font-weight:600;line-height:1.4;">Coordinación General de Tecnologías de Información</div>
                                            </div>
                                        </div>
                                        <div style="height:1px;background:rgba(255,255,255,.20);margin:15px 0 15px;"></div>
                                        <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:15px;">
                                            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:20px;">
                                                <a href="https://uady.mx" target="_blank" style="color:white;text-decoration:none;display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;">
                                                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="7.25" stroke="currentColor" stroke-width="1.5"></circle><path d="M15.25 12C15.25 16.5 13.24 19.25 12 19.25C10.76 19.25 8.75 16.5 8.75 12C8.75 7.5 10.76 4.75 12 4.75C13.24 4.75 15.25 7.5 15.25 12Z" stroke="currentColor" stroke-width="1.5"></path><path d="M5 12H19" stroke="currentColor" stroke-width="1.5"></path></svg>
                                                    UADY
                                                </a>
                                                <a href="mailto:atencion.uadyvirtual@correo.uady.mx" style="color:white;text-decoration:none;display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;word-break:break-word;">
                                                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M4.75 7.75C4.75 6.64 5.64 5.75 6.75 5.75H17.25C18.35 5.75 19.25 6.64 19.25 7.75V16.25C19.25 17.35 18.35 18.25 17.25 18.25H6.75C5.64 18.25 4.75 17.35 4.75 16.25V7.75Z" stroke="currentColor" stroke-width="1.5"></path><path d="M5.5 6.5L12 12.25L18.5 6.5" stroke="currentColor" stroke-width="1.5"></path></svg>
                                                    atencion.uadyvirtual@correo.uady.mx
                                                </a>
                                            </div>
                                            <div style="display:flex;align-items:center;gap:15px;">
                                                <a href="https://www.facebook.com/uadyvirtual" target="_blank" style="color:white;text-decoration:none;display:flex;align-items:center;justify-content:center;"><svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12C2 16.84 5.44 20.87 10 21.8V14.89H7.5V12H10V9.8C10 7.3 11.49 5.92 13.78 5.92C14.87 5.92 16 6.11 16 6.11V8.56H14.75C13.52 8.56 13.14 9.32 13.14 10.1V12H15.88L15.44 14.89H13.14V21.8C17.7 20.87 21.14 16.84 21.14 12C21.14 6.48 16.66 2 12 2Z"/></svg></a>
                                                <a href="https://www.youtube.com/@tecnologia_uady" target="_blank" style="color:white;text-decoration:none;display:flex;align-items:center;justify-content:center;"><svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor"><path d="M21.58 7.19C21.35 6.33 20.67 5.65 19.81 5.42C18.25 5 12 5 12 5C12 5 5.75 5 4.19 5.42C3.33 5.65 2.65 6.33 2.42 7.19C2 8.75 2 12 2 12C2 12 2 15.25 2.42 16.81C2.65 17.67 3.33 18.35 4.19 18.58C5.75 19 12 19 12 19C12 19 18.25 19 19.81 18.58C20.67 18.35 21.35 17.67 21.58 16.81C22 15.25 22 12 22 12C22 12 22 8.75 21.58 7.19ZM10 15.5V8.5L16 12L10 15.5Z"/></svg></a>
                                            </div>
                                        </div>
                                    </footer>
                                </div>
                            </div>
                        </div>
