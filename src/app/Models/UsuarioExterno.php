<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UsuarioExterno extends Model
{
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
    ];

    protected $hidden = ['password'];

    // Hashear contraseña automáticamente al asignarla
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Relaciones con catálogos
    public function dependencia()
    {
        return $this->belongsTo(Catalogo::class, 'id_dependencia');
    }

    public function programa()
    {
        return $this->belongsTo(Catalogo::class, 'id_programa');
    }

    public function rol()
    {
        return $this->belongsTo(Catalogo::class, 'id_rol');
    }

    public function semestre()
    {
        return $this->belongsTo(Catalogo::class, 'id_semestre');
    }
}
