<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Crear usuario externo
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="mb-4 text-red-600">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('external-users.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Usuario --}}
                            <div>
                                <label class="block font-medium text-sm">Usuario</label>
                                <input type="text" name="username" value="{{ old('username') }}"
                                    class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                            </div>

                            {{-- Contraseña --}}
                            <div>
                                <label class="block font-medium text-sm">Contraseña</label>
                                <input type="password" name="password"
                                    class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                                <small class="text-gray-500 text-xs">Mín. 8 caracteres</small>
                            </div>

                            {{-- Nombre --}}
                            <div>
                                <label class="block font-medium text-sm">Nombre</label>
                                <input type="text" name="firstname" value="{{ old('firstname') }}"
                                    class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                            </div>

                            {{-- Apellido --}}
                            <div>
                                <label class="block font-medium text-sm">Apellido</label>
                                <input type="text" name="lastname" value="{{ old('lastname') }}"
                                    class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                            </div>

                            {{-- Correo --}}
                            <div>
                                <label class="block font-medium text-sm">Correo electrónico</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                            </div>

                            {{-- CURP --}}
                            <div>
                                <label class="block font-medium text-sm">CURP <span class="text-gray-400 font-normal">(opcional)</span></label>
                                <input type="text" name="curp" value="{{ old('curp') }}"
                                    maxlength="18"
                                    placeholder="XXXX000000XXXXXX00"
                                    style="text-transform:uppercase"
                                    class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm font-mono">
                            </div>

                            {{-- Dependencia --}}
                            <div>
                                <label class="block font-medium text-sm">Dependencia</label>
                                <select name="id_dependencia"
                                        class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach ($dependencias as $item)
                                        <option value="{{ $item->id }}" {{ old('id_dependencia') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Programa --}}
                            <div>
                                <label class="block font-medium text-sm">Programa</label>
                                <select name="id_programa"
                                        class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach ($programas as $item)
                                        <option value="{{ $item->id }}" {{ old('id_programa') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Rol --}}
                            <div>
                                <label class="block font-medium text-sm">Rol</label>
                                <select name="id_rol"
                                        class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach ($roles as $item)
                                        <option value="{{ $item->id }}" {{ old('id_rol') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Semestre --}}
                            <div>
                                <label class="block font-medium text-sm">Semestre</label>
                                <select name="id_semestre"
                                        class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach ($semestres as $item)
                                        <option value="{{ $item->id }}" {{ old('id_semestre') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <a href="{{ route('dashboard') }}"
                               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg text-sm">
                                Volver
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm">
                                Crear usuario
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
