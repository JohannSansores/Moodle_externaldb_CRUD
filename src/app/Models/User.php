<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable; // removed HasApiTokens

    protected $fillable = [
        'name',
        'surname',
        'email',
        'curp',
        'id_dependencia',
        'id_programa',
        'role_id',
        'semester_id',
        'password',
        'role', // <-- added
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
