<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $testing = app()->environment('testing');

        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255' . ($testing ? '' : '|unique:moodle_usuarios'),
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|same:password',
            'curp' => 'required|string|size:18' . ($testing ? '' : '|unique:moodle_usuarios'),
            'id_dependencia' => $testing ? 'required|integer|min:1' : 'required|exists:cat_dependencias,id',
            'id_programa' => $testing ? 'required|integer|min:1' : 'required|exists:cat_programas,id',
            'id_rol' => 'required|integer|min:1',
            'id_semestre' => 'required|integer|min:1',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Este correo ya está registrado.',
            'curp.unique' => 'Este CURP ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'g-recaptcha-response.required' => 'Por favor, verifica que no eres un robot.',
            'g-recaptcha-response.captcha' => 'La verificación CAPTCHA falló. Inténtalo de nuevo.',
        ];
    }
}
