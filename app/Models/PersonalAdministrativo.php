<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class PersonalAdministrativo extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'personal_administrativo';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'activo',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}