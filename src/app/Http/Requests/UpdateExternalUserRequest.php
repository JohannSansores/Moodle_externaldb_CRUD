<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExternalUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Obtiene el ID del usuario desde la URL para excluirlo del unique
        $id = $this->route('external_user');

        return [
            'username'       => "required|string|max:100|unique:usuarios_externos,username,{$id}",
            'firstname'      => 'required|string|max:100',
            'lastname'       => 'required|string|max:100',
            'email'          => 'required|email|max:150',
            'id_dependencia' => 'required|integer|exists:cat_dependencias,id',
            'id_programa'    => 'required|integer|exists:cat_programas,id',
            'id_rol'         => 'required|integer|exists:cat_roles,id',
            'id_semestre'    => 'required|integer|exists:cat_semestres,id',
            'password'       => 'nullable|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required'       => 'El nombre de usuario es obligatorio.',
            'username.unique'         => 'Este nombre de usuario ya está en uso.',
            'password.min'            => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'firstname.required'      => 'El nombre es obligatorio.',
            'lastname.required'       => 'El apellido es obligatorio.',
            'email.required'          => 'El correo electrónico es obligatorio.',
            'email.email'             => 'El correo electrónico no tiene un formato válido.',
            'id_dependencia.required' => 'Debes seleccionar una dependencia.',
            'id_dependencia.exists'   => 'La dependencia seleccionada no existe.',
            'id_programa.required'    => 'Debes seleccionar un programa.',
            'id_programa.exists'      => 'El programa seleccionado no existe.',
            'id_rol.required'         => 'Debes seleccionar un rol.',
            'id_rol.exists'           => 'El rol seleccionado no existe.',
            'id_semestre.required'    => 'Debes seleccionar un semestre.',
            'id_semestre.exists'      => 'El semestre seleccionado no existe.',
        ];
    }
}
