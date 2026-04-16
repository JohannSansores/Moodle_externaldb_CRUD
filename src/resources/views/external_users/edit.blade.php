<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar usuario externo
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="mb-4 text-red-600">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('external-users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Usuario --}}
                            <div>
                                <label class="block text-sm font-medium">Usuario</label>
                                <input type="text" name="username"
                                       value="{{ old('username', $user->username) }}"
                                       class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                            </div>

                            {{-- Contraseña --}}
                            <div>
                                <label class="block text-sm font-medium">
                                    Nueva contraseña <span class="text-gray-400 font-normal">(dejar vacío para mantener)</span>
                                </label>
                                <input type="password" name="password"
                                       class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm">
                                <small class="text-gray-500 text-xs">Mín. 8 caracteres</small>
                            </div>

                            {{-- Nombre --}}
                            <div>
                                <label class="block text-sm font-medium">Nombre</label>
                                <input type="text" name="firstname"
                                       value="{{ old('firstname', $user->firstname) }}"
                                       class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                            </div>

                            {{-- Apellido --}}
                            <div>
                                <label class="block text-sm font-medium">Apellido</label>
                                <input type="text" name="lastname"
                                       value="{{ old('lastname', $user->lastname) }}"
                                       class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                            </div>

                            {{-- Correo --}}
                            <div>
                                <label class="block text-sm font-medium">Correo electrónico</label>
                                <input type="email" name="email"
                                       value="{{ old('email', $user->email) }}"
                                       class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                            </div>

                            {{-- CURP --}}
                            <div>
                                <label class="block text-sm font-medium">CURP <span class="text-gray-400 font-normal">(opcional)</span></label>
                                <input type="text" name="curp"
                                       value="{{ old('curp', $user->curp) }}"
                                       maxlength="18"
                                       placeholder="XXXX000000XXXXXX00"
                                       style="text-transform:uppercase"
                                       class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm font-mono">
                            </div>

                            {{-- Dependencia --}}
                            <div>
                                <label class="block text-sm font-medium">Dependencia</label>
                                <select name="id_dependencia"
                                        class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach ($dependencias as $dep)
                                        <option value="{{ $dep->id }}" @selected($dep->id == $user->id_dependencia)>
                                            {{ $dep->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Programa --}}
                            <div>
                                <label class="block text-sm font-medium">Programa</label>
                                <select name="id_programa"
                                        class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach ($programas as $prog)
                                        <option value="{{ $prog->id }}" @selected($prog->id == $user->id_programa)>
                                            {{ $prog->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Rol --}}
                            <div>
                                <label class="block text-sm font-medium">Rol</label>
                                <select name="id_rol"
                                        class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol->id }}" @selected($rol->id == $user->id_rol)>
                                            {{ $rol->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Semestre --}}
                            <div>
                                <label class="block text-sm font-medium">Semestre</label>
                                <select name="id_semestre"
                                        class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach ($semestres as $sem)
                                        <option value="{{ $sem->id }}" @selected($sem->id == $user->id_semestre)>
                                            {{ $sem->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <a href="{{ route('dashboard') }}"
                               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg text-sm">
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm">
                                Guardar cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
