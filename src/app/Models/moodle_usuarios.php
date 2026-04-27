<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;

class moodle_usuarios extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $table = 'moodle_usuarios';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'password',
        'firstname',
        'lastname',
        'email',
        'curp',
        'id_dependencia',
        'id_programa',
        'id_rol',
        'id_semestre',
        'fechacreacion',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Alias de campos para usar name/surname/dependencia/programa/rol/semestre
    public function getNameAttribute()
    {
        return $this->firstname;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['firstname'] = $value;
    }

    public function getSurnameAttribute()
    {
        return $this->lastname;
    }

    public function setSurnameAttribute($value)
    {
        $this->attributes['lastname'] = $value;
    }

    public function getDependenciaAttribute()
    {
        return $this->attributes['id_dependencia'];
    }

    public function setDependenciaAttribute($value)
    {
        $this->attributes['id_dependencia'] = $value;
    }

    public function getProgramaAttribute()
    {
        return $this->attributes['id_programa'];
    }

    public function setProgramaAttribute($value)
    {
        $this->attributes['id_programa'] = $value;
    }

    public function getRolAttribute()
    {
        return $this->attributes['id_rol'];
    }

    public function setRolAttribute($value)
    {
        $this->attributes['id_rol'] = $value;
    }

    public function getSemestreAttribute()
    {
        return $this->attributes['id_semestre'];
    }

    public function setSemestreAttribute($value)
    {
        $this->attributes['id_semestre'] = $value;
    }

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
