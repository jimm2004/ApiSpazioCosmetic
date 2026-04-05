<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'activo', // NUEVO CAMPO
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'activo' => 'boolean', // Asegura que Laravel lo trate como true/false
    ];

    public function sendPasswordResetNotification($token)
    {
        // Llama a la notificación personalizada que acabamos de crear
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }
}