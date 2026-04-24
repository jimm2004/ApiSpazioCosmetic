<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carritos';

    protected $fillable = [
        'user_id',
        'estado',
        'subtotal',
        'total',
        'observacion',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // =========================================================
    // RELACIÓN: CARRITO PERTENECE A UN USUARIO
    // =========================================================
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // =========================================================
    // RELACIÓN: CARRITO TIENE MUCHOS DETALLES
    // =========================================================
    public function detalles()
    {
        return $this->hasMany(CarritoDetalle::class, 'carrito_id', 'id');
    }

    // =========================================================
    // RELACIÓN: CARRITO PUEDE GENERAR UN PEDIDO
    // =========================================================
    public function pedido()
    {
        return $this->hasOne(Pedido::class, 'carrito_id', 'id');
    }

    // =========================================================
    // RECALCULAR TOTALES DEL CARRITO
    // =========================================================
    public function recalcularTotales()
    {
        $subtotal = $this->detalles()->sum('subtotal');

        $this->subtotal = $subtotal;
        $this->total = $subtotal;
        $this->save();

        return $this;
    }
}