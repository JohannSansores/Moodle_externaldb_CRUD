<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Cache;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'curp' => strtoupper($this->curp),
            'email' => strtolower($this->email),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $testing = app()->environment('testing');
        $tablaUsuarios  = 'moodle_usuarios';
        $tablaDependencias = 'cat_dependencias';
        $tablaProgramas = 'cat_programas';

        // Leer config del registro
        $cfg  = Cache::get('register_form_config', []);
        $flds = $cfg['fields'] ?? [];
        $show = fn(string $k) => (bool)($flds[$k]['enabled'] ?? true);

        return [
            // Si el campo está desactivado → nullable, si está activo → required
            'name'    => $show('name')    ? 'required|string|min:2|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/' : 'nullable|string|max:255',
            'surname' => $show('surname') ? 'required|string|min:2|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/' : 'nullable|string|max:255',
            'username' => $show('username') ? [
                'required','string','min:4','max:20',
                'regex:/^[a-zA-Z0-9._-]+$/',
                $testing ? '' : "unique:{$tablaUsuarios},username"
            ] : 'nullable|string|max:20',
            'email_confirmation' => $show('email') ? 'required' : 'nullable',
            'email' => $show('email')
                ? 'required|string|email|max:255|confirmed' . ($testing ? '' : "|unique:{$tablaUsuarios},email")
                : 'nullable|string|email|max:255',
            'password'              => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'password_confirmation' => 'required|same:password',
            'curp' => $show('curp') ? [
                'required','string','size:18',
                'regex:/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[A-Z0-9][0-9]$/',
                $testing ? '' : "unique:{$tablaUsuarios},curp"
            ] : 'nullable|string|max:18',
            'id_dependencia' => $show('dependencia')
                ? ($testing ? 'required|integer|min:1' : "required|exists:{$tablaDependencias},id")
                : 'nullable|integer',
            'id_programa' => $show('programa')
                ? ($testing ? 'required|integer|min:1' : "required|exists:{$tablaProgramas},id")
                : 'nullable|integer',
            'id_rol'      => 'required|integer|min:1',
            'id_semestre' => 'required|integer|min:1',
            'g-recaptcha-response' => $testing ? 'nullable' : 'required|captcha',
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'El nombre solo puede contener letras.',
            'surname.regex' => 'Los apellidos solo pueden contener letras.',
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'username.regex' => 'El nombre de usuario solo puede contener letras, números, puntos, guiones y guiones bajos.',
            'username.min' => 'El usuario debe tener al menos 4 caracteres.',
            'email.unique' => 'Este correo ya está registrado.',
            'email.email' => 'Formato de correo inválido.',
            'email.confirmed' => 'Los correos electrónicos no coinciden.',
            'curp.unique' => 'Esta CURP ya está registrada.',
            'curp.regex' => 'El formato de la CURP es incorrecto.',
            'curp.size' => 'La CURP debe tener exactamente 18 caracteres.',
            'curp.string' => 'La CURP debe ser una cadena de texto válida.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password_confirmation.same' => 'La confirmación de la contraseña debe coincidir con la contraseña.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password' => 'La contraseña debe incluir letras y números.',
            'id_dependencia.exists' => 'La dependencia seleccionada no es válida.',
            'id_programa.exists' => 'El programa seleccionado no es válido.',
            'g-recaptcha-response.required' => 'Verifica que no eres un robot.',
            'g-recaptcha-response.captcha' => 'Error en la validación CAPTCHA.',
        ];
    }
}
