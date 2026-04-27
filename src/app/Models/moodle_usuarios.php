<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;

class moodle_usuarios extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $table = 'usuarios_externos';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'password',
        'firstname',
        'lastname',
        'email',
        'id_dependencia',
        'id_programa',
        'id_rol',
        'id_semestre',
        'fechacreacion',
        'curp',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Para autenticación
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    // Para email verification
    public function getEmailForVerification()
    {
        return $this->email;
    }

    public function getRememberToken()
    {
        return null; // Si no tienes campo remember_token
    }

    public function setRememberToken($value)
    {
        // No hacer nada si no tienes el campo
    }

    public function getRememberTokenName()
    {
        return null;
    }
}
