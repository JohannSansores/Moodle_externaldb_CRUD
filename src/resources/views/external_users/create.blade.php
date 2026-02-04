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

                    {{-- Validation errors --}}
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

                        {{-- Username --}}
                        <div class="mb-3">
                            <label class="block font-medium">Usuario</label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm"
                                required>
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label class="block font-medium">Contraseña</label>
                            <input type="password" name="password"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm"
                                required>
                            <small class="text-gray-500 text-sm">
                                Mín. 8 caracteres (compatible con bcrypt / Moodle)
                            </small>
                        </div>

                        {{-- Firstname --}}
                        <div class="mb-3">
                            <label class="block font-medium">Nombre</label>
                            <input type="text" name="firstname" value="{{ old('firstname') }}"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm"
                                required>
                        </div>

                        {{-- Lastname --}}
                        <div class="mb-3">
                            <label class="block font-medium">Apellido</label>
                            <input type="text" name="lastname" value="{{ old('lastname') }}"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm"
                                required>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="block font-medium">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm"
                                required>
                        </div>

                        {{-- Dependencia --}}
                        <div class="mb-3">
                            <label class="block font-medium">Dependencia</label>
                            <select name="id_dependencia"
                                    class="w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm"
                                    required>
                                <option value="">-- Seleccionar --</option>
                                @foreach ($dependencias as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('id_dependencia') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Programa --}}
                        <div class="mb-3">
                            <label class="block font-medium">Programa</label>
                            <select name="id_programa"
                                    class="w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm"
                                    required>
                                <option value="">-- Seleccionar --</option>
                                @foreach ($programas as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('id_programa') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Rol --}}
                        <div class="mb-3">
                            <label class="block font-medium">Rol</label>
                            <select name="id_rol"
                                    class="w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm"
                                    required>
                                <option value="">-- Seleccionar --</option>
                                @foreach ($roles as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('id_rol') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Semestre --}}
                        <div class="mb-4">
                            <label class="block font-medium">Semestre</label>
                            <select name="id_semestre"
                                    class="w-full rounded border-gray-300 dark:bg-gray-700 px-3 py-2 text-sm"
                                    required>
                                <option value="">-- Seleccionar --</option>
                                @foreach ($semestres as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('id_semestre') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('dashboard') }}"
                               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg">
                                Volver
                            </a>

                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                                Crear usuario
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
