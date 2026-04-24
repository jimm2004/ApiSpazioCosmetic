<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $table = 'envios';

    protected $fillable = [
        'nombre',
        'tipo_calculo',
        'porcentaje_envio',
        'monto_fijo',
        'minimo_compra',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'porcentaje_envio' => 'decimal:2',
        'monto_fijo' => 'decimal:2',
        'minimo_compra' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'envio_id');
    }
}