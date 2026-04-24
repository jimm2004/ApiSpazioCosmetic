<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'user_id',
        'datos_cliente_id',
        'carrito_id',
        'estado_pedido_id',
        'envio_id',
        'codigo_pedido',
        'nombres_cliente',
        'apellidos_cliente',
        'telefono_cliente',
        'direccion_cliente',
        'departamento_id',
        'municipio_id',
        'referencia_direccion',
        'subtotal',
        'descuento',
        'impuesto',
        'costo_envio',
        'total',
        'total_final',
        'direccion_entrega',
        'telefono_contacto',
        'fecha_pedido',
        'fecha_entrega',
        'observacion',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'impuesto' => 'decimal:2',
        'costo_envio' => 'decimal:2',
        'total' => 'decimal:2',
        'total_final' => 'decimal:2',
        'fecha_pedido' => 'datetime',
        'fecha_entrega' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function datosCliente()
    {
        return $this->belongsTo(DatosCliente::class, 'datos_cliente_id');
    }

    public function carrito()
    {
        return $this->belongsTo(Carrito::class, 'carrito_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoPedido::class, 'estado_pedido_id');
    }

    public function envio()
    {
        return $this->belongsTo(Envio::class, 'envio_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }

    // Pago único por tarjeta en línea
    public function pago()
    {
        return $this->hasOne(Pago::class, 'pedido_id');
    }
}