<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Porteiro extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'cpf', 'matricula','role', 'turno', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

}

