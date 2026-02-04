<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit External User
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Validation errors --}}
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

                        {{-- Username --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium">Username</label>
                            <input type="text"
                                   name="username"
                                   value="{{ old('username', $user->username) }}"
                                   class="mt-1 block w-full rounded"
                                   required>
                        </div>

                        {{-- First name --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium">First name</label>
                            <input type="text" name="firstname"
                                   value="{{ old('firstname', $user->firstname) }}"
                                   class="mt-1 block w-full rounded" required>
                        </div>

                        {{-- Last name --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium">Last name</label>
                            <input type="text" name="lastname"
                                   value="{{ old('lastname', $user->lastname) }}"
                                   class="mt-1 block w-full rounded" required>
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium">Email</label>
                            <input type="email" name="email"
                                   value="{{ old('email', $user->email) }}"
                                   class="mt-1 block w-full rounded" required>
                        </div>

                        {{-- Password --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium">
                                New Password (leave empty to keep current)
                            </label>
                            <input type="password" name="password"
                                   class="mt-1 block w-full rounded">
                        </div>

                        {{-- Dependencia --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium">Dependencia</label>
                            <select name="id_dependencia" class="mt-1 block w-full rounded" required>
                                @foreach ($dependencias as $dep)
                                    <option value="{{ $dep->id }}" @selected($dep->id == $user->id_dependencia)>
                                        {{ $dep->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Programa --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium">Programa</label>
                            <select name="id_programa" class="mt-1 block w-full rounded" required>
                                @foreach ($programas as $prog)
                                    <option value="{{ $prog->id }}" @selected($prog->id == $user->id_programa)>
                                        {{ $prog->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Rol --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium">Rol</label>
                            <select name="id_rol" class="mt-1 block w-full rounded" required>
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->id }}" @selected($rol->id == $user->id_rol)>
                                        {{ $rol->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Semestre --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium">Semestre</label>
                            <select name="id_semestre" class="mt-1 block w-full rounded" required>
                                @foreach ($semestres as $sem)
                                    <option value="{{ $sem->id }}" @selected($sem->id == $user->id_semestre)>
                                        {{ $sem->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-between">
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:underline">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded">
                                Update User
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
