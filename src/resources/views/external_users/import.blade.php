<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Importar usuarios desde CSV
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Instrucciones --}}
            <div class="bg-blue-50 dark:bg-gray-800 border border-blue-200 dark:border-gray-700 rounded-lg p-5">
                <h3 class="font-semibold text-blue-800 dark:text-blue-300 mb-2">📋 Instrucciones</h3>
                <ul class="text-sm text-blue-700 dark:text-blue-400 space-y-1 list-disc list-inside">
                    <li>El archivo debe ser <strong>.csv</strong> con codificación <strong>UTF-8</strong>.</li>
                    <li>La primera fila debe contener los encabezados exactamente como se indica.</li>
                    <li>Los campos <strong>dependencia</strong>, <strong>programa</strong>, <strong>rol</strong> y <strong>semestre</strong> deben coincidir con los nombres registrados en los catálogos.</li>
                    <li>La contraseña debe tener mínimo 8 caracteres.</li>
                    <li>Si un usuario ya existe (mismo username), esa fila se omitirá.</li>
                </ul>

                {{-- Plantilla descargable --}}
                <div class="mt-4">
                    <a href="{{ route('external-users.import.template') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium
                              bg-blue-600 text-white hover:bg-blue-700">
                        ⬇️ Descargar plantilla CSV
                    </a>
                </div>
            </div>

            {{-- Formulario de carga --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 rounded bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('external-users.import') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Seleccionar archivo CSV
                        </label>
                        <input type="file"
                               name="csv_file"
                               accept=".csv,.txt"
                               required
                               class="block w-full text-sm text-gray-700 dark:text-gray-300
                                      file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                      file:text-sm file:font-medium file:bg-blue-600 file:text-white
                                      hover:file:bg-blue-700 cursor-pointer">
                        <p class="mt-1 text-xs text-gray-500">Máximo 2 MB.</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('dashboard') }}"
                           class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg text-sm">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm">
                            📤 Importar usuarios
                        </button>
                    </div>
                </form>
            </div>

            {{-- Formato esperado --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-3">Formato del CSV</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs text-left border border-gray-200 dark:border-gray-700 rounded">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase">
                            <tr>
                                <th class="px-3 py-2 border-b dark:border-gray-600">Columna</th>
                                <th class="px-3 py-2 border-b dark:border-gray-600">Ejemplo</th>
                                <th class="px-3 py-2 border-b dark:border-gray-600">Notas</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr><td class="px-3 py-2 font-mono font-semibold">username</td><td class="px-3 py-2">jperez2024</td><td class="px-3 py-2">Único, sin espacios</td></tr>
                            <tr><td class="px-3 py-2 font-mono font-semibold">password</td><td class="px-3 py-2">MiClave123</td><td class="px-3 py-2">Mínimo 8 caracteres</td></tr>
                            <tr><td class="px-3 py-2 font-mono font-semibold">firstname</td><td class="px-3 py-2">Juan</td><td class="px-3 py-2">Nombre(s)</td></tr>
                            <tr><td class="px-3 py-2 font-mono font-semibold">lastname</td><td class="px-3 py-2">Pérez</td><td class="px-3 py-2">Apellido(s)</td></tr>
                            <tr><td class="px-3 py-2 font-mono font-semibold">email</td><td class="px-3 py-2">jperez@mail.com</td><td class="px-3 py-2">Correo válido</td></tr>
                            <tr><td class="px-3 py-2 font-mono font-semibold">dependencia</td><td class="px-3 py-2">Facultad de Ingeniería</td><td class="px-3 py-2">Nombre exacto del catálogo</td></tr>
                            <tr><td class="px-3 py-2 font-mono font-semibold">programa</td><td class="px-3 py-2">Sistemas Computacionales</td><td class="px-3 py-2">Nombre exacto del catálogo</td></tr>
                            <tr><td class="px-3 py-2 font-mono font-semibold">rol</td><td class="px-3 py-2">Estudiante</td><td class="px-3 py-2">Nombre exacto del catálogo</td></tr>
                            <tr><td class="px-3 py-2 font-mono font-semibold">semestre</td><td class="px-3 py-2">Primero</td><td class="px-3 py-2">Nombre exacto del catálogo</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 bg-gray-50 dark:bg-gray-900 rounded p-3">
                    <p class="text-xs font-mono text-gray-500 dark:text-gray-400 leading-relaxed">
                        username,password,firstname,lastname,email,dependencia,programa,rol,semestre<br>
                        jperez2024,MiClave123,Juan,Pérez,jperez@mail.com,Facultad de Ingeniería,Sistemas Computacionales,Estudiante,Primero
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
