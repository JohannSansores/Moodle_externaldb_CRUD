<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

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

        $tablaUsuarios = 'usuarios_externos'; 
        $tablaDependencias = 'cat_dependencias'; 
        $tablaProgramas = 'cat_programas';

        return [
        'name' => 'required|string|min:2|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
        'surname' => 'required|string|min:2|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
        'email' => 'required|string|email|max:255' . ($testing ? '' : "|unique:{$tablaUsuarios},email"),
        'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        'password_confirmation' => 'required|same:password',
        'curp' => [
            'required', 'string', 'size:18', 
            'regex:/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[A-Z0-9][0-9]$/',
            $testing ? '' : "unique:{$tablaUsuarios},curp"
        ],
        'id_dependencia' => $testing ? 'required|integer|min:1' : "required|exists:{$tablaDependencias},id",
        'id_programa' => $testing ? 'required|integer|min:1' : "required|exists:{$tablaProgramas},id",
        'id_rol' => 'required|integer|min:1',
        'id_semestre' => 'required|integer|min:1',
        'g-recaptcha-response' => $testing ? 'nullable' : 'required|captcha',
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'El nombre solo puede contener letras.',
            'surname.regex' => 'Los apellidos solo pueden contener letras.',
            'email.unique' => 'Este correo ya está registrado.',
            'email.email' => 'Formato de correo inválido.',
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
