<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class moodle_usuarios extends Model
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
}
