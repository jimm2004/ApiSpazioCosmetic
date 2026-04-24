<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'pedido_id',
        'monto',
        'moneda',
        'metodo',
        'proveedor_pasarela',
        'transaccion_id',
        'authorization_code',
        'tarjeta_marca',
        'tarjeta_ultimos4',
        'estado',
        'fecha_pago',
        'respuesta_pasarela',
        'observacion',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
        'respuesta_pasarela' => 'array',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
}