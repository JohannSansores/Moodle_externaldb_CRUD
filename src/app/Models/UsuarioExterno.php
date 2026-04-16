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
        'curp',
        'id_dependencia',
        'id_programa',
        'id_rol',
        'id_semestre',
        'fechacreacion',
    ];

    protected $hidden = ['password'];

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

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
