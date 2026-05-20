<x-guest-layout>

    <x-slot name="head">
        <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    </x-slot>

    <div class="w-full sm:max-w-2xl mx-auto mt-10 mb-10 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-2xl overflow-hidden">
        <div class="px-8 pt-10 pb-4 flex justify-center">
            <div class="flex items-center justify-center p-2 transition-transform duration-500 hover:scale-105">
                <img src="{{ asset('images/logo_uady.svg') }}" 
                    alt="Logo UADY" 
                    class="h-32 w-auto drop-shadow-lg filter brightness-100 dark:brightness-90 contrast-125" />
            </div>
        </div>
    </div>
        
        <!-- Título mejorado -->
    <h1 class="text-4xl font-bold text-center tracking-wide text-[black] dark:text-[white] inline-block mx-auto mb-6 block">
            Registro de Usuario
        
    </h1>
    
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nombre" class="text-black"/>
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Surname -->
        <div class="mt-4">
            <x-input-label for="surname" value="Apellido" class="text-black"/>
            <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" :value="old('surname')" required autocomplete="family-name" />
            <x-input-error :messages="$errors->get('surname')" class="mt-2" />
        </div>

        <!-- Username -->
        <div class="mt-4">
            <x-input-label for="username" value="Nombre de Usuario" class="text-black"/>
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autocomplete="username" 
                oninput="validarCampoRealTime('username', this.value)" />
            <div id="feedback-username" class="text-xs mt-1 hidden"></div>
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" value="Correo electrónico" class="text-black"/>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" 
                oninput="validarCampoRealTime('email', this.value)" />
            <div id="feedback-email" class="text-xs mt-1 hidden"></div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Confirm Email Address -->
        <div class="mt-4">
            <x-input-label for="email_confirmation" value="Confirmar correo electrónico" class="text-black"/>
            <x-text-input id="email_confirmation" class="block mt-1 w-full" type="email" name="email_confirmation" required 
                oninput="compararCorreos()" />
            <div id="feedback-email-conf" class="text-xs mt-1 hidden"></div>
            <x-input-error :messages="$errors->get('email_confirmation')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Contraseña" class="text-black"/>

            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password"
                                oninput="validarPasswordRealTime(this.value)" />
                <div id="feedback-password" class="text-xs mt-1 hidden"></div>

                <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar contraseña" class="text-black"/>

            <div class="relative">
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" 
                            oninput="compararPasswords()" />

                <div id="feedback-password-conf" class="text-xs mt-1 hidden"></div>

                <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>  

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Curp -->
        <div class="mt-4">
            <x-input-label for="curp" value="CURP" class="text-black"/>
            <x-text-input id="curp" class="block mt-1 w-full" type="text" name="curp" 
                :value="old('curp')" required 
                oninput="validarCampoRealTime('curp', this.value)" />
            <div id="feedback-curp" class="text-xs mt-1 hidden"></div>
            <x-input-error :messages="$errors->get('curp')" class="mt-2" />
        </div>

        <!-- Dependency -->
        <div class="mt-4">
            <x-input-label for="id_dependencia" value="Dependencia" class="text-black"/>
            <select id="id_dependencia" name="id_dependencia" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">-- Seleccionar --</option>
                @foreach($dependencias as $dep)
                    <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                @endforeach
            </select>
        </div>

         <!-- Programa -->
        <div class="mt-4">
            <x-input-label for="id_programa" value="Programa" class="text-black"/>
            <select id="id_programa" name="id_programa" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">-- Seleccionar --</option>
                @foreach($programas as $prog)
                    <option value="{{ $prog->id }}">{{ $prog->nombre }}</option>
                @endforeach
            </select>
        </div>

        <input type="hidden" name="id_rol" value="3">
        <input type="hidden" name="id_semestre" value="1">

        <!-- CAPTCHA -->
        <div class="mt-4">
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display() !!}
            <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#003B5C]" href="{{ route('admin.login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 bg-[#003B5C] hover:bg-[#002a44]">
                {{ __('Register') }}
            </x-primary-button>
        </div>
      
    </form>
    <footer class="mt-10 bg-[#BD8F1E] h-8"></footer>
    <script>
        let timers = {};

    function validarCampoRealTime(field, value) {
        const feedback = document.getElementById(`feedback-${field}`);
        
        const labelNames = {
                'username': 'Nombre de usuario',
                'email': 'Correo electrónico',
                'curp': 'CURP'
            };

        // Limpiar timer anterior
        clearTimeout(timers[field]);

    // CURP requires a longer minimum length to avoid unnecessary database hits
        let minLength = (field === 'curp') ? 10 : 3;

        if (value.length < minLength) {
            feedback.classList.add('hidden');
            return;
        }

        timers[field] = setTimeout(() => {
            // Normalizing data before sending: Lowercase for emails, Uppercase for CURP
            const normalizedValue = field === 'email' ? value.toLowerCase() : (field === 'curp' ? value.toUpperCase() : value);

            fetch("{{ route('validate.field') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ 
                    field: field, 
                    value: normalizedValue 
                })
            })
            .then(response => response.json())
            .then(data => {
                feedback.classList.remove('hidden');
                
                if (data.status === 'error') {
                    feedback.textContent = data.message;
                    feedback.className = "text-xs mt-1 text-red-600 dark:text-red-400 font-bold";
                } else {
                    // Determine the success message
                    if (field === 'curp') {
                        feedback.textContent = "✓ CURP válido";
                    } else {
                        const friendlyName = labelNames[field] || field;
                        feedback.textContent = `✓ ${friendlyName} disponible`;
                    }

                    feedback.className = "text-xs mt-1 text-green-600 dark:text-green-400 font-bold";
                }
            })
            .catch(error => console.error('Error in real-time validation:', error));
        }, 500);
    }

    function compararCorreos() {
        const email = document.getElementById('email').value;
        const confirm = document.getElementById('email_confirmation').value;
        const feedback = document.getElementById('feedback-email-conf');

        if (confirm.length === 0) {
            feedback.classList.add('hidden');
            return;
        }

        feedback.classList.remove('hidden');
        if (email !== confirm) {
            feedback.textContent = "Los correos no coinciden.";
            feedback.className = "text-xs mt-1 text-red-600 dark:text-red-400 font-bold";
        } else {
            feedback.textContent = "✓ Confirmación correcta";
            feedback.className = "text-xs mt-1 text-green-600 dark:text-green-400 font-bold";
        }
    }

    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const svg = button.querySelector('svg');
        
        if (input.type === "password") {
            input.type = "text";
            // Change the Icon (Eye Slash)
            svg.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.96 9.96 0 012.39-3.79m15.02 0c.93 1.157 1.63 2.503 2.052 3.96a10.038 10.038 0 01-1.29 2.65m-2.65 2.65l-1.39-1.39m-1.39-1.39l-1.39-1.39m-1.39-1.39l-1.39-1.39m-1.39-1.39l-1.39-1.39M9.88 9.88l-1.39-1.39m1.39 1.39l1.39 1.39m0 0l1.39 1.39m0 0l1.39 1.39M3 3l18 18" />
            `;
        } else {
            input.type = "password";
            // Original Icon (Eye)
            svg.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    }

    function validarPasswordRealTime(value) {
    const feedback = document.getElementById('feedback-password');
    feedback.classList.remove('hidden');
    
    // Reglas de validación (Moodle suele requerir al menos 8 caracteres)
    if (value.length < 8) {
        feedback.textContent = "La contraseña debe tener al menos 8 caracteres.";
        feedback.className = "text-xs mt-1 text-red-600 font-bold";
    } else {
        feedback.textContent = "✓ Contraseña segura";
        feedback.className = "text-xs mt-1 text-green-600 font-bold";
    }
    // También validamos si coincide con la confirmación si es que ya escribió algo ahí
    compararPasswords();
}

function compararPasswords() {
    const pass = document.getElementById('password').value;
    const conf = document.getElementById('password_confirmation').value;
    const feedback = document.getElementById('feedback-password-conf');

    if (conf.length === 0) {
        feedback.classList.add('hidden');
        return;
    }

    feedback.classList.remove('hidden');
    if (pass !== conf) {
        feedback.textContent = "Las contraseñas no coinciden.";
        feedback.className = "text-xs mt-1 text-red-600 font-bold";
    } else {
        feedback.textContent = "✓ Las contraseñas coinciden";
        feedback.className = "text-xs mt-1 text-green-600 font-bold";
    }
}
</script>
</x-guest-layout>

<!--
<div style="width: 100%; height: 200px; overflow: hidden;">
  <img src="{{ asset('images/edificio_central.jpg') }}" alt="Edificio Central UADY" style="width: 100%; height: 100%; object-fit: fill;" />
</div>
-->