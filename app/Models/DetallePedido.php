<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedidos';

    protected $fillable = [
        'pedido_id',
        'producto_master_id',
        'producto_imagen_id',
        'nombre_producto',
        'precio_unitario',
        'precio_final',
        'cantidad',
        'subtotal',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'precio_final' => 'decimal:2',
        'cantidad' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_master_id', 'id_producto');
    }

    public function productoImagen()
    {
        return $this->belongsTo(ProductoImagen::class, 'producto_imagen_id');
    }
}