<?php

namespace App\Models;

use App\Notifications\CustomResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'activo' => 'boolean',
    ];

    // =========================================================
    // NOTIFICACIÓN PERSONALIZADA PARA RECUPERAR CONTRASEÑA
    // =========================================================
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    // =========================================================
    // RELACIÓN: UN USUARIO TIENE DATOS DE CLIENTE
    // =========================================================
    public function datosCliente()
    {
        return $this->hasOne(DatosCliente::class, 'user_id', 'id');
    }

    // =========================================================
    // RELACIÓN: UN USUARIO PUEDE TENER VARIOS PEDIDOS
    // =========================================================
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'user_id', 'id');
    }

    // =========================================================
    // RELACIÓN: UN USUARIO PUEDE TENER VARIOS CARRITOS
    // =========================================================
    public function carritos()
    {
        return $this->hasMany(Carrito::class, 'user_id', 'id');
    }
    public function carritoActivo()
{
    return $this->hasOne(Carrito::class, 'user_id', 'id')
        ->where('estado', 'activo');
}
}